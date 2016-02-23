<?php

namespace Veridu\API;

use Veridu\Exception;

class Check extends AbstractEndpoint {
	const NONE = 0x0000;
	const TRACESMART_ALL = 0xFFFF;
	const TRACESMART_ADDRESS = 0x0001;
	const TRACESMART_DOB = 0x0002;
	const TRACESMART_DRIVERLICENSE = 0x0004;
	const TRACESMART_PASSPORT = 0x0008;
	const TRACESMART_CREDITACTIVE = 0x0010;

	const FILTER_ALL = 'all';
	const FILTER_STATE = 'state';
	const FILTER_INFO = 'info';

	private function tracesmartSetup($map) {
		$filter = array();

		if (($map & self::TRACESMART_ADDRESS) == self::TRACESMART_ADDRESS)
			$filter[] = 'address';

		if (($map & self::TRACESMART_DOB) == self::TRACESMART_DOB)
			$filter[] = 'dob';

		if (($map & self::TRACESMART_DRIVERLICENSE) == self::TRACESMART_DRIVERLICENSE)
			$filter[] = 'driving';

		if (($map & self::TRACESMART_PASSPORT) == self::TRACESMART_PASSPORT)
			$filter[] = 'passport';

		if (($map & self::TRACESMART_CREDITACTIVE) == self::TRACESMART_CREDITACTIVE)
			$filter[] = 'credit-active';

		return $filter;
	}

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/Check_Resource#How_to_create_a_new_Background_Check
	*
	* @param string $username User identification
	*
	* @return self
	*/
	public function create($provider, $setup = self::NONE, $additionalParams = array(), $username = null) {
		if (empty($username)) {
			if ($this->storage->isUsernameEmpty())
				throw new \Veridu\API\Exception\EmptyUsername;
			$username = $this->storage->getUsername();
		} else if (!self::validateUsername($username))
			throw new \Veridu\API\Exception\InvalidUsername;

		switch ($provider) {
			case 'tracesmart':
				$setup = $this->tracesmartSetup($setup);
				break;
			default:
				throw new \Veridu\API\Exception\InvalidProvider;
		}

		$json = $this->signedFetch(
			'POST',
			sprintf(
				'check/%s/%s',
				$username,
				$provider
			),
			array(
				'setup' => implode(',', $setup),
				'param' => implode(',', $additionalParams)
			)
		);

		return $json['task_id'];
	}

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/Check_Resource#How_to_retrieve_data_from_all_providers
	*
	* @param string $username User identification
	*
	* @return array
	*/
	public function listAll($username = null) {
		if (empty($username)) {
			if ($this->storage->isUsernameEmpty())
				throw new \Veridu\API\Exception\EmptyUsername;
			$username = $this->storage->getUsername();
		} else if (!self::validateUsername($username))
			throw new \Veridu\API\Exception\InvalidUsername;

		$json = $this->signedFetch(
				'GET',
				sprintf(
					'check/%s',
					$username
				)
			);
		return $json['data'];
	}

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/Check_Resource#How_to_retrieve_data_from_one_provider
	*
	* @param string $username User identification
	*
	* @return array
	*/
	public function details($username, $provider, $setup, $filter = Check::FILTER_ALL) {
		if (empty($username)) {
			if ($this->storage->isUsernameEmpty())
				throw new \Veridu\API\Exception\EmptyUsername;
			$username = $this->storage->getUsername();
		} else if (!self::validateUsername($username))
			throw new \Veridu\API\Exception\InvalidUsername;

		$json = $this->signedFetch(
			'GET',
			sprintf(
				'check/%s/%s/?filter=%s&setup=%s',
				$username,
				$provider,
				$filter,
				$setup
			)
		);

		return $json['data'];
	}
}

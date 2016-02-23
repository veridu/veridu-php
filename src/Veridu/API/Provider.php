<?php

namespace Veridu\API;

use Veridu\API\Exception;

class Provider extends AbstractEndpoint {

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/Provider_Resource#How_to_create_a_access_token_under_given_user_for_the_given_provider
	*
	* @return integer Task ID
	*
	* @throws Veridu\Exception\EmptySession
	* @throws Veridu\Exception\EmptyUsername
	* @throws Veridu\Exception\InvalidResponse
	* @throws Veridu\Exception\InvalidFormat
	* @throws Veridu\Exception\APIError
	*/
	public function createOAuth1($provider, $token, $secret, $appId = null) {
		$this->validateNotEmptySessionOrFail();

		$username = $this->storage->getUsername();
		self::validateNotEmptyUsernameOrFail($username);

		$json = $this->fetch(
			'POST',
			sprintf(
				'provider/%s/%s',
				$username,
				$provider
			),
			array(
				'token' => $token,
				'secret' => $secret,
				'appid' => $appId
			)
		);

		return $json['task_id'];
	}

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/Provider_Resource#How_to_create_a_access_token_under_given_user_for_the_given_provider
	*
	* @return integer Task ID
	*
	* @throws Veridu\Exception\EmptySession
	* @throws Veridu\Exception\EmptyUsername
	* @throws Veridu\Exception\InvalidResponse
	* @throws Veridu\Exception\InvalidFormat
	* @throws Veridu\Exception\APIError
	*/
	public function createOAuth2($provider, $token, $appId = null) {
		$this->validateNotEmptySessionOrFail();

		$username = $this->storage->getUsername();
		self::validateNotEmptyUsernameOrFail($username);

		$json = $this->fetch(
			'POST',
			sprintf(
				'provider/%s/%s',
				$this->storage->getUsername(),
				$provider
			),
			array(
				'token' => $token,
				'appid' => $appId
			)
		);

		return $json['task_id'];
	}

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/Provider_Resource#How_to_retrieve_a_list_of_all_providers_a_given_user_used_to_verify_himself
	*
	* @param string $username User identification
	*
	* @return array
	*
	* @throws Veridu\Exception\EmptySession
	* @throws Veridu\Exception\EmptyUsername
	* @throws Veridu\Exception\InvalidUsername
	* @throws Veridu\Exception\InvalidResponse
	* @throws Veridu\Exception\InvalidFormat
	* @throws Veridu\Exception\APIError
	*/
	public function getAllDetails($username = null) {
		$this->validateNotEmptySessionOrFail();

		$username = empty($username) ? $this->storage->getUsername() : $username;
		self::validateUsernameOrFail($username);

		$json = $this->fetch(
			'GET',
			sprintf(
				'provider/%s/all',
				$username
			)
		);

		return $json;
	}

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/Provider_Resource#How_to_retrieve_a_list_of_all_providers_a_given_user_used_to_verify_himself
	*
	* @param string $username User identification
	*
	* @return array
	*
	* @throws Veridu\Exception\EmptySession
	* @throws Veridu\Exception\EmptyUsername
	* @throws Veridu\Exception\InvalidUsername
	* @throws Veridu\Exception\InvalidResponse
	* @throws Veridu\Exception\InvalidFormat
	* @throws Veridu\Exception\APIError
	*/
	public function listAll($username = null) {
		$this->validateNotEmptySessionOrFail();

		$username = empty($username) ? $this->storage->getUsername() : $username;
		self::validateUsernameOrFail($username);

		$json = $this->fetch(
			'GET',
			sprintf(
				'provider/%s/state',
				$username
			)
		);

		return $json['list'];
	}

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/Provider_Resource#How_to_retrieve_if_the_given_user_has_used_the_given_provider_as_a_verification_method
	*
	* @param string $provider Provider name
	* @param string $username User identification
	*
	* @return boolean
	*
	* @throws Veridu\Exception\EmptySession
	* @throws Veridu\Exception\EmptyUsername
	* @throws Veridu\Exception\InvalidUsername
	* @throws Veridu\Exception\InvalidResponse
	* @throws Veridu\Exception\InvalidFormat
	* @throws Veridu\Exception\APIError
	*/
	public function check($provider, $username = null) {
		$this->validateNotEmptySessionOrFail();

		$username = empty($username) ? $this->storage->getUsername() : $username;
		self::validateUsernameOrFail($username);

		$json = $this->fetch(
			'GET',
			sprintf(
				'provider/%s/%s/state',
				$username,
				$provider
			)
		);

		return ($json['state'] === true);
	}

}

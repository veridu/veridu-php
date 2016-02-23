<?php

namespace Veridu\API;

use Veridu\Exception;

final class KBA extends AbstractEndpoint {

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/KBA_Resource#How_to_retrieve_a_list_with_all_KBA_methods_that_the_given_user_has_used_to_verify_himself
	*
	* @param string $username User identification
	*
	* @return array
	*/
	public function listAll($username = null) {
		if ($this->storage->isSessionEmpty())
			throw new Exception\EmptySession;

		if (empty($username))
			$username = $this->storage->getUsername();
		else if (!self::validateUsername($username))
			throw new Exception\InvalidUsername;

		$json = $this->fetch(
			'GET',
			sprintf(
				'kba/%s',
				$username
			)
		);

		return $json['list'];
	}

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/KBA_Resource#How_to_create_a_KBA_method_under_a_give_user
	*
	* @return array|null
	*/
	public function createSpotAFriend() {
		if ($this->storage->isSessionEmpty())
			throw new Exception\EmptySession;

		if ($this->storage->isUsernameEmpty())
			throw new Exception\EmptyUsername;

		$json = $this->fetch(
			'POST',
			sprintf(
				'kba/%s',
				$this->storage->getUsername()
			)
		);

		return $json['setup'];
	}

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/KBA_Resource#How_to_retrieve_if_the_given_user_has_used_the_given_KBA_method_name_as_a_verification_method
	*
	* @param string $username User identification
	*
	* @return boolean
	*/
	public function verifiedWithSpotAFriend($username = null) {
		if ($this->storage->isSessionEmpty())
			throw new Exception\EmptySession;

		if (empty($username))
			$username = $this->storage->getUsername();
		else if (!self::validateUsername($username))
			throw new Exception\InvalidUsername;

		$json = $this->fetch(
			'GET',
			sprintf(
				'kba/%s/spotafriend',
				$username
			)
		);

		return ($json['state'] === true);
	}

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/KBA_Resource#How_to_verify_the_KBA_method_name_identified_by_id.2C_created_under_user
	*
	* @return self
	*/
	public function verifySpotAFriend($spotafriendId, $target) {
		if ($this->storage->isSessionEmpty())
			throw new Exception\EmptySession;

		if ($this->storage->isUsernameEmpty())
			throw new Exception\EmptyUsername;

		$this->fetch(
			'PUT',
			sprintf(
				'kba/%s/spotafriend/%d',
				$this->storage->getUsername(),
				$spotafriendId
			),
			array(
				'target' => $target
			)
		);

		return $this;
	}

}

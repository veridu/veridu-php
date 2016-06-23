<?php

namespace Veridu\API;

use Veridu\Exception;

 class KBA extends AbstractEndpoint {

	/**
	* Lists all KBA methods that the given user has used to verify himself
	* 
	* @link https://veridu.com/wiki/KBA_Resource#How_to_retrieve_a_list_with_all_KBA_methods_that_the_given_user_has_used_to_verify_himself
	*
	* @param string $username User identification
	*
	* @return array
	*/
	public function listAll($username = null) {
		$this->validateNotEmptySessionOrFail();
		$username = empty($username) ? $this->storage->getUsername() : $username;
		self::validateUsernameOrFail($username);
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
	* creates a KBA method under a given user
	*
	* @link https://veridu.com/wiki/KBA_Resource#How_to_create_a_KBA_method_under_a_give_user
	*
	* @return array|null
	*/
	public function createSpotAFriend() {
		$this->validateNotEmptySessionOrFail();
		$username = $this->storage->getUsername();
		self::validateNotEmptyUsernameOrFail($username);

		$json = $this->fetch(
			'POST',
			sprintf(
				'kba/%s/spotafriend',
				$username
			)
		);

		return $json['setup'];
	}

	/**
	* Retrieves state if the given user has used the given KBA method name as a verification method
	*
	* @link https://veridu.com/wiki/KBA_Resource#How_to_retrieve_if_the_given_user_has_used_the_given_KBA_method_name_as_a_verification_method
	*
	* @param string $username User identification
	*
	* @return boolean
	*/
	public function verifiedWithSpotAFriend($username = null) {
		$this->validateNotEmptySessionOrFail();
		$username = empty($username) ? $this->storage->getUsername() : $username;
		self::validateUsernameOrFail($username)y 
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
	* Verifies the KBA method name identified by id creted under user
	*
	* @link https://veridu.com/wiki/KBA_Resource#How_to_verify_the_KBA_method_name_identified_by_id.2C_created_under_user
	*
	* @return self
	*/
	public function verifySpotAFriend($spotafriendId, $target) {
		$this->validateNotEmptySessionOrFail();
		$username = $this->storage->getUsername();
		self::validateNotEmptyUsernameOrFail($username);
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

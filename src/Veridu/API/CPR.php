<?php

namespace Veridu\API;

use Veridu\Exception;

final class CPR extends AbstractEndpoint {

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/CPR_Resource#How_to_create_a_new_CPR_verification_for_the_given_user
	*
	* @return self
	*/
	public function create($number) {
	}

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/CPR_Resource#How_to_retrieve_the_verification_information_from_a_give_user
	*
	* @param string $username User identification
	*
	* @return array
	*/
	public function details($username = null) {
	}

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/CPR_Resource#How_to_retrieve_the_verification_information_from_a_give_user
	*
	* @param string $username User identification
	*
	* @return boolean
	*/
	public function isVerified($username = null) {
	}
}

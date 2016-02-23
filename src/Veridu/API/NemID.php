<?php

namespace Veridu\API;

use Veridu\Exception;

final class NemID extends AbstractEndpoint {

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/NemID_Resource#How_to_retrieve_the_verification_information_for_the_given_user
	*
	* @param string $username User identification
	*
	* @return array
	*/
	public function details($filter = NemID::FILTER_ALL, $username = null) {
	}

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/NemID_Resource#How_to_retrieve_the_verification_information_for_the_given_user
	*
	* @param string $username User identification
	*
	* @return boolean
	*/
	public function isVerified($username = null) {
	}

}

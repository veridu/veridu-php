<?php

namespace Veridu\API;

use Veridu\Exception;

final class Raw extends AbstractEndpoint {

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/Raw_Resource#How_to_retrieve_a_user.27s_raw_profile_data
	*
	* @param string $username User identification
	*
	* @return array
	*/
	public function retrieveData($username, $provider = null) {
	}

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/Raw_Resource#How_to_retrieve_a_user.27s_credential_data
	*
	* @param string $username User identification
	*
	* @return array
	*/
	public function retrieveCredentials($username, $provider = null) {
	}

}

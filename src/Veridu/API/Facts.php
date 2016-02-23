<?php

namespace Veridu\API;

use Veridu\Exception;

final class Facts extends AbstractEndpoint {

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/Facts_Resource#How_to_retrieve_a_full_facts_list_of_a_provided_user
	*
	* @param string $username User identification
	*
	* @return array
	*/
	public function listAll($username = null) {
	}

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/Facts_Resource#How_to_retrieve_a_full_facts_list_of_a_provided_user_for_a_specific_provider
	*
	* @param string $username User identification
	*
	* @return array
	*/
	public function retrieve($provider, $username = null) {
	}

}

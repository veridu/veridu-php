<?php

namespace Veridu\API;

use Veridu\Exception;

final class Certificate extends AbstractEndpoint {

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/Certificate_Resource#How_to_retrieve_a_list_of_certificates_for_a_given_user
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
	* @link https://veridu.com/wiki/Certificate_Resource#How_to_retrieve_a_certificate_info_for_the_given_user
	*
	* @param string $username User identification
	*
	* @return array
	*/
	public function details($certificate, $username = null) {
	}

}

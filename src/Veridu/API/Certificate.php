<?php

namespace Veridu\API;

use Veridu\Exception;

 class Certificate extends AbstractEndpoint {

	/**
	* Lists all certificates for a given user
	*
	* @link https://veridu.com/wiki/Certificate_Resource#How_to_retrieve_a_list_of_certificates_for_a_given_user
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
					'certificate/%s',
					$username
				)
		);
		return $json['list'];
	}

	/**
	* Gives certificate info for the given user
	*
	* @link https://veridu.com/wiki/Certificate_Resource#How_to_retrieve_a_certificate_info_for_the_given_user
	*
	* @param string $username User identification
	*
	* @return array
	*/
	public function details($certificate, $username = null) {
		$this->validateNotEmptySessionOrFail();
		$username = empty($username) ? $this->storage->getUsername() : $username;
		self::validateUsernameOrFail($username);

		$json = $this->fetch(
				'GET',
				sprintf(
					'certificate/%s/%s',
					$username,
					$certificate
				)
		);
		return $json['certificate'];
	}

}

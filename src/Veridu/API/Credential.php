<?php

namespace Veridu\API;

use Veridu\Exception;

class Credential extends AbstractEndpoint {

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/Credential_Resource#How_to_retrieve_general_session_information
	*
	* @return array
	*
	* @throws Veridu\Exception\EmptySession
	* @throws Veridu\Exception\InvalidResponse
	* @throws Veridu\Exception\InvalidFormat
	* @throws Veridu\Exception\APIError
	*/
	public function details() {
		if ($this->storage->isSessionEmpty())
			throw new \Veridu\API\Exception\EmptySession;

		$json = $this->fetch(
			'GET',
			'credential'
		);

		return $json;
	}

}

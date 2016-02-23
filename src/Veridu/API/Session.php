<?php
/**
* Session management
*/

namespace Veridu\API;

use Veridu\API\Exception;

class Session extends AbstractEndpoint {

	/**
	* Creates a new session
	*
	* @link https://veridu.com/wiki/Session_Resource#How_to_create_a_limited_lifetime_session_token
	*
	* @param boolean $readonly Session read-only permission
	*
	* @return string Session token
	*
	* @throws Veridu\Exception\InvalidResponse
	* @throws Veridu\Exception\InvalidFormat
	* @throws Veridu\Exception\APIError
	* @throws Veridu\Exception\NonceMismatch
	*/
	public function create($readonly = true) {
		if ($readonly)
			$json = $this->signedFetch(
				'POST',
				'session/read'
			);
		else
			$json = $this->signedFetch(
				'POST',
				'session/write'
			);

		$this->storage->setSessionToken($json['token']);
		$this->storage->setSessionExpires($json['expires']);

		return $json['token'];
	}

	/**
	* Extends the current session lifetime
	*
	* @link https://veridu.com/wiki/Session_Resource#How_to_extend_the_lifetime_of_a_session
	*
	* @return self
	*
	* @throws Veridu\Exception\InvalidResponse
	* @throws Veridu\Exception\InvalidFormat
	* @throws Veridu\Exception\APIError
	* @throws Veridu\Exception\NonceMismatch
	* @throws Veridu\Exception\EmptySession
	*/
	public function extend() {
		if ($this->storage->isSessionEmpty())
			throw new Exception\EmptySession;

		$json = $this->signedFetch(
			'PUT',
			sprintf(
				'session/%s',
				$this->storage->getSessionToken()
			)
		);
		$this->storage->setSessionExpires($json['expires']);
		return $this;
	}

	/**
	* Expires the current session
	*
	* @link https://veridu.com/wiki/Session_Resource#How_to_delete_the_given_session_token_before_it_expires
	*
	* @return self
	*
	* @throws Veridu\Exception\InvalidResponse
	* @throws Veridu\Exception\InvalidFormat
	* @throws Veridu\Exception\APIError
	* @throws Veridu\Exception\NonceMismatch
	* @throws Veridu\Exception\EmptySession
	*/

	public function expire() {
		if ($this->storage->isSessionEmpty())
			throw new Exception\EmptySession;

		$this->signedFetch(
			'DELETE',
			sprintf(
				'session/%s',
				$this->storage->getSessionToken()
			)
		);
		$this->storage->purgeSession();
		return $this;
	}

}

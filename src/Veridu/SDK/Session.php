<?php
/**
*	Session management
*/

namespace Veridu\SDK;

class Session {
	/**
	* @var API API Instance
	*/
	private $api;
	/**
	* @var integer Session expire unixtime
	*/
	private $expires = -1;
	/**
	* @var string Username identification
	*/
	private $username = null;

	/**
	* Class constructor
	*
	* @param API $api API object
	*
	* @return void
	*/
	public function __construct(API &$api) {
		$this->api = $api;
	}

	/**
	* Sets the API object
	*
	* @param API $api API object
	*
	* @return void
	*/
	public function setAPI(API &$api) {
		$this->api = $api;
	}

	/**
	* Returns the API object
	*
	* @return API
	*/
	public function getAPI() {
		return $this->api;
	}

	/**
	* Sets session token
	*
	* @param string $value Token value
	*
	* @return void
	*/
	public function setToken($value) {
		$this->api->setSession($value);
	}

	/**
	* Returns the session token
	*
	* @return string|null
	*/
	public function getToken() {
		return $this->api->getSession();
	}

	/**
	* Sets the session expire unixtime
	*
	* @param integer $value Expire unixtime value
	*
	* @return void
	*/
	public function setExpires($value) {
		$this->expires = $value;
	}

	/**
	* Returns the expire unixtime
	*
	* @return integer
	*/
	public function getExpires() {
		return intval($this->expires);
	}

	/**
	* Sets the username identification
	*
	* @param string $value Username identification
	*
	* @return void
	*/
	public function setUsername($value) {
		$this->username = $value;
	}

	/**
	* Returns the username identification
	*
	* @return string
	*/
	public function getUsername() {
		return $this->username;
	}

	/**
	* Creates a new session if one isn't already created
	*
	* @param boolean $readonly Session read-only permission
	*
	* @return void
	*
	* @throws InvalidResponse
	* @throws InvalidFormat
	* @throws APIError
	* @throws NonceMismatch
	*/
	public function create($readonly = true) {
		if (is_null($this->api->getSession())) {
			if ($readonly)
				$json = $this->api->signedFetch('POST', 'session/read');
			else
				$json = $this->api->signedFetch('POST', 'session/write');
			$this->api->setSession($json['token']);
			$this->setExpires($json['expires']);
		}
	}

	/**
	* Extends the current session lifetime
	*
	* @return void
	*
	* @throws InvalidResponse
	* @throws InvalidFormat
	* @throws APIError
	* @throws NonceMismatch
	* @throws EmptySession
	*/
	public function extend() {
		$session = $this->api->getSession();
		if (is_null($session))
			throw new Exception\EmptySession;
		$json = $this->api->signedFetch('PUT', "session/{$session}");
		$this->setExpires($json['expires']);
	}

	/**
	* Expires the current session
	*
	* @return void
	*
	* @throws InvalidResponse
	* @throws InvalidFormat
	* @throws APIError
	* @throws NonceMismatch
	* @throws EmptySession
	*/
	public function expire() {
		$session = $this->api->getSession();
		if (is_null($session))
			throw new Exception\EmptySession;
		$this->api->signedFetch('DELETE', "session/{$session}");
		$this->api->purgeSession();
		$this->setExpires(-1);
		$this->setUsername(null);
	}

	/**
	* Assigns the current session to a user (if the user doesn't exists, it will be created)
	*
	* @param string $username User identification
	*
	* @return void
	*
	* @throws InvalidResponse
	* @throws InvalidFormat
	* @throws APIError
	* @throws NonceMismatch
	* @throws EmptySession
	* @throws InvalidUsername
	*/
	public function assign($username) {
		if (is_null($this->api->getSession()))
			throw new Exception\EmptySession;
		if (!preg_match('/^[a-zA-Z0-9_-]+$/', $username))
			throw new Exception\InvalidUsername;
		$this->api->signedFetch('POST', "user/{$username}");
		$this->setUsername($username);
	}

}
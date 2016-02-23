<?php

namespace Veridu\Storage;

final class SessionStorage implements StorageInterface {
	/**
	* @var string $_SESSION namespace
	*/
	private $namespace;

	/**
	* Returns current session status
	*
	* @return boolean
	*/
	private function sessionStatus() {
		if (version_compare(phpversion(), '5.4.0', '>='))
            return (session_status() === PHP_SESSION_ACTIVE);
        return (session_id() !== '');
    }

    /**
    * Class constructor
    *
    * @param string $namespace $_SESSION namespace
    *
    * @return void
    */
	public function __construct($namespace = 'veridu') {
		if (!$this->sessionStatus())
			session_start();
		if (empty($namespace))
			$namespace = 'veridu';
		$this->namespace = $namespace;
	}

	/**
	* {@inheritDoc}
	*/
	public function setSessionToken($token) {
		$_SESSION[$this->namespace]['token'] = $token;
		return $this;
	}

	/**
	* {@inheritDoc}
	*/
	public function getSessionToken() {
		if (isset($_SESSION[$this->namespace]['token']))
			return $_SESSION[$this->namespace]['token'];
		return null;
	}

	/**
	* {@inheritDoc}
	*/
	public function setSessionExpires($expires) {
		$_SESSION[$this->namespace]['expires'] = $expires;
		return $this;
	}

	/**
	* {@inheritDoc}
	*/
	public function getSessionExpires() {
		if (isset($_SESSION[$this->namespace]['expires']))
			return intval($_SESSION[$this->namespace]['expires']);
		return -1;
	}

	/**
	* {@inheritDoc}
	*/
	public function purgeSession() {
		unset($_SESSION[$this->namespace]['token']);
		unset($_SESSION[$this->namespace]['expires']);
		return $this;
	}

	/**
	* {@inheritDoc}
	*/
	public function isSessionEmpty() {
		return empty($_SESSION[$this->namespace]['token']);
	}

	/**
	* {@inheritDoc}
	*/
	public function setUsername($username) {
		$_SESSION[$this->namespace]['username'] = $username;
		return $this;
	}

	/**
	* {@inheritDoc}
	*/
	public function getUsername() {
		if (isset($_SESSION[$this->namespace]['username']))
			return $_SESSION[$this->namespace]['username'];
		return null;
	}

	/**
	* {@inheritDoc}
	*/
	public function purgeUsername() {
		unset($_SESSION[$this->namespace]['username']);
		return $this;
	}

	/**
	* {@inheritDoc}
	*/
	public function isUsernameEmpty() {
		return empty($_SESSION[$this->namespace]['username']);
	}

}

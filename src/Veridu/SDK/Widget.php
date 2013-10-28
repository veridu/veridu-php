<?php

namespace Veridu\SDK;

/**
*	Widget endpoint handling
*/
class Widget {
	private $client;
	private $version;
	private $session;
	private $username;

	const BASE_URL = 'https://widget.veridu.com';

	/**
	* @param string $client
	* @param string $version
	* @param string $session
	* @param string $username
	*
	* @return void
	*/
	public function __construct($client, $version, $session, $username) {
		$this->client = $client;
		$this->version = $version;
		$this->session = $session;
		$this->username = $username;
	}

	/**
	* @param string $value
	*
	* @return void
	*/
	public function setClient($value) {
		$this->client = $value;
	}

	/**
	* @return string
	*/
	public function getClient() {
		return $this->client;
	}

	/**
	* @param string $value
	*
	* @return void
	*/
	public function setVersion($value) {
		$this->version = $value;
	}

	/**
	* @return string
	*/
	public function getVersion() {
		return $this->version;
	}

	/**
	* @param string $value
	*
	* @return void
	*/
	public function setSession($value) {
		$this->session = $value;
	}

	/**
	* @return string
	*/
	public function getSession() {
		return $this->session;
	}

	/**
	* @param string $value
	*
	* @return void
	*/
	public function setUsername($value) {
		$this->username = $value;
	}

	/**
	* @return string
	*/
	public function getUsername() {
		return $this->username;
	}

	/**
	* @param string $resource
	* @param string/array $query
	*
	* @return string
	*/
	public function getEndpoint($resource, $query = null) {
		if (empty($this->username))
			throw new Exception\MissingUsername;
		$resource = trim($resource, '/');
		if (is_null($query))
			$query = "session={$this->session}";
		else if (is_array($query)) {
			$query['session'] = $this->session;
			$query = http_build_query($query, '', '&', PHP_QUERY_RFC1738);
		} else
			$query .= "&session={$this->session}";
		return sprintf("%s/%s/%s/%s/%s?%s", self::BASE_URL, $this->version, $resource, $this->client, $this->username, $query);
	}

}
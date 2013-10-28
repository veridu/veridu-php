<?php

namespace Veridu\SDK;

use Veridu\HTTPClient\HTTPClient;
use Veridu\Signature\Signature;

/**
*	Full API access
*/
class API {
	private $client;
	private $secret;
	private $version;
	private $http;
	private $signature;
	private $session = null;
	private $username = null;

	const BASE_URL = 'https://api.veridu.com';
	const VERSION = '0.2.0';

	/**
	* @param string $resource
	* @param string/array $query
	*
	* @return string
	*/
	private function buildURL($resource, $query = null) {
		$resource = ltrim($resource, '/');
		if (is_array($query))
			$query = http_build_query($query, '', '&', PHP_QUERY_RFC1738);
		if (!empty($query)) {
			if (strpos($resource, '?') === false)
				$resource .= "?{$query}";
			else
				$resource .= "&{$query}";
		}
		return sprintf("%s/%s/%s", self::BASE_URL, $this->version, $resource);
	}

	/**
	* @param string $client
	* @param string $secret
	* @param string $version
	* @param HTTPClient $http
	* @param Signature $signature
	*
	* @return void
	*/
	public function __construct($client, $secret, $version, HTTPClient &$http, Signature &$signature) {
		$this->client = $client;
		$this->secret = $secret;
		$this->version = $version;
		$this->setHTTP($http);
		$this->signature = $signature;
	}

	/**
	* @param string $method
	* @param string $resource
	* @param string/array $data
	*
	* @return string
	*
	* @throws InvalidMethod InvalidFormat InvalidResponse APIError
	*/
	public function fetch($method, $resource, $data = null) {
		switch ($method) {
			case 'GET':
				$response = $this->http->GET($this->buildURL($resource, $data));
				break;
			case 'POST':
				$response = $this->http->POST($this->buildURL($resource), $data);
				break;
			case 'DELETE':
				$response = $this->http->DELETE($this->buildURL($resource), $data);
				break;
			case 'PUT':
				$response = $this->http->PUT($this->buildURL($resource), $data);
				break;
			default:
				throw new Exception\InvalidMethod;
		}
		$json = json_decode($response, true);
		if (is_null($json))
			throw new Exception\InvalidFormat($response);
		if (!isset($json['status']))
			throw new Exception\InvalidResponse($json);
		if ($json['status'] === false) {
			$this->lastError = $json['error']['type'];
			throw new Exception\APIError($json['error']['message']);
		}
		return $json;
	}

	/**
	* @param string $method
	* @param string $resource
	*
	* @return string
	*
	* @throws InvalidMethod InvalidFormat InvalidResponse APIError NonceMismatch
	*/
	public function signedFetch($method, $resource) {
		$sign = $this->signature->sign($method, $this->buildURL($resource));
		$json = $this->fetch($method, $resource, $sign);
		if ((empty($json['nonce'])) || (strcmp($json['nonce'], $this->signature->lastNonce()) != 0))
			throw new Signature\Exception\NonceMismatch;
		unset($json['nonce']);
		return $json;
	}

	/**
	* @param string $value
	*
	* @return void
	*/
	public function setClient($value) {
		$this->client = $value;
		$this->http->setHeader('Veridu-Client', $value);
		$this->signature->setClient($value);
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
	public function setSecret($value) {
		$this->secret = $value;
		$this->signature->setSecret($value);
	}

	/**
	* @return string
	*/
	public function getSecret() {
		return $this->secret;
	}

	/**
	* @param string $value
	*
	* @return void
	*/
	public function setVersion($value) {
		$this->version = $value;
		$this->signature->setVersion($value);
	}

	/**
	* @return string
	*/
	public function getVersion() {
		return $this->version;
	}

	/**
	* @param HTTPClient $http
	*
	* @return void
	*/
	public function setHTTP(HTTPClient &$http) {
		$this->http = $http;
		$this->http->setHeader('Veridu-Client', $this->client);
		if (!is_null($this->session))
			$this->http->setHeader('Veridu-Session', $this->session['token']);
		$this->http->setUserAgent('Veridu-PHP/' . self::VERSION);
	}

	/**
	* @return HTTPClient
	*/
	public function getHTTP() {
		return $this->http;
	}

	/**
	* @param Signature $signature
	*
	* @return void
	*/
	public function setSignature(Signature &$signature) {
		$this->signature = $signature;
	}

	/**
	* @return Signature
	*/
	public function getSignature() {
		return $this->signature;
	}

	/**
	* @param string $value
	*
	* @return void
	*/
	public function setSession($value) {
		$this->session['token'] = $value;
		$this->http->setHeader('Veridu-Session', $value);
	}

	/**
	* @return string
	*/
	public function getSession() {
		if (empty($this->session['token']))
			return null;
		return $this->session['token'];
	}

	/**
	* @param integer $value
	*
	* @return void
	*/
	public function setExpires($value) {
		$this->session['expires'] = $value;
	}

	/**
	* @return integer
	*/
	public function getExpires() {
		if (empty($this->session['expires']))
			return null;
		return intval($this->session['expires']);
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
	* @return string
	*/
	public function lastError() {
		return $this->lastError;
	}

	/**
	* @param boolean $readonly
	*
	* @return void
	*
	* @throws InvalidMethod InvalidResponse InvalidFormat APIError NonceMismatch
	*/
	public function sessionCreate($readonly = true) {
		if (is_null($this->session)) {
			if ($readonly)
				$json = $this->signedFetch('POST', 'session');
			else
				$json = $this->signedFetch('POST', 'session/write');
			$this->setSession($json['token']);
			$this->setExpires($json['expires']);
		}
	}

	/**
	* @return void
	*
	* @throws InvalidMethod InvalidResponse InvalidFormat APIError NonceMismatch EmptySession
	*/
	public function sessionExtend() {
		if (is_null($this->session))
			throw new Exception\EmptySession;
		$json = $this->signedFetch('PUT', "session/{$this->session['token']}");
		$this->setExpires($json['expires']);
	}

	/**
	* @return void
	*
	* @throws InvalidMethod InvalidResponse InvalidFormat APIError NonceMismatch EmptySession
	*/
	public function sessionExpire() {
		if (is_null($this->session))
			throw new Exception\EmptySession;
		$json = $this->signedFetch('DELETE', "session/{$this->session['token']}");
		$this->http->unsetHeader('Veridu-Session');
		$this->session = null;
		$this->setUsername(null);
	}

	/**
	* @return void
	*
	* @throws InvalidMethod InvalidResponse InvalidFormat APIError NonceMismatch EmptySession InvalidUsername
	*/
	public function userCreate($username) {
		if (is_null($this->session))
			throw new Exception\EmptySession;
		if (!preg_match('/^[a-zA-Z0-9_-]+$/', $username))
			throw new Exception\InvalidUsername;
		$json = $this->signedFetch('POST', "user/{$username}");
		$this->setUsername($username);
	}

}

<?php
/**
* Full API client
*/

namespace Veridu\SDK;

use Veridu\Common\Config;
use Veridu\Common\Compat;
use Veridu\Common\URL;
use Veridu\HTTPClient\HTTPClient;
use Veridu\Signature\Signature;

class API {
	/**
	* @var Config Basic client configuration
	*/
	private $config;
	/**
	* @var HTTPClient HTTP Client to perform API requests
	*/
	private $http;
	/**
	* @var Signature object for signed API requests
	*/
	private $signature;
	/**
	* @var string Session token
	*/
	private $session = null;
	/**
	* @var string Last API call error
	*/
	private $lastError = null;

	/**
	* Base API URL
	*/
	const BASE_URL = 'https://api.veridu.com';

	/**
	* Class constructor
	*
	* @param Config $config Basic client configuration
	* @param HTTPClient $http HTTP Client to perform API requests
	* @param Signature $signature Signature object for signed API requests
	*
	* @return void
	*/
	public function __construct(Config &$config, HTTPClient &$http, Signature &$signature) {
		$this->config = $config;
		$this->setHTTP($http);
		$this->setSignature($signature);
	}

	/**
	* Fetches an API resource
	*
	* @param string $method Request method
	* @param string $resource Resource URI
	* @param string|array $data Request payload/query string
	*
	* @return string
	*
	* @throws Exception\InvalidMethod
	* @throws Exception\InvalidFormat
	* @throws Exception\InvalidResponse
	* @throws Exception\APIError
	*/
	public function fetch($method, $resource, $data = null) {
		$this->lastError = null;
		switch (strtoupper($method)) {
			case 'GET':
				$response = $this->http->GET(URL::build(self::BASE_URL, array($this->config->getVersion(), $resource), $data));
				break;
			case 'POST':
				$response = $this->http->POST(URL::build(self::BASE_URL, array($this->config->getVersion(), $resource)), $data);
				break;
			case 'DELETE':
				$response = $this->http->DELETE(URL::build(self::BASE_URL, array($this->config->getVersion(), $resource)), $data);
				break;
			case 'PUT':
				$response = $this->http->PUT(URL::build(self::BASE_URL, array($this->config->getVersion(), $resource)), $data);
				break;
			default:
				throw new Exception\InvalidMethod;
		}
		$json = json_decode($response, true);
		if (is_null($json))
			throw new Exception\InvalidFormat($response);
		if (!isset($json['status']))
			throw new Exception\InvalidResponse($response);
		if ($json['status'] === false) {
			$this->lastError = $json['error']['type'];
			throw new Exception\APIError($json['error']['message']);
		}
		return $json;
	}

	/**
	* Fetches a signed API resource
	*
	* @param string $method Request method
	* @param string $resource Resource URI
	* @param string|array $data Request payload/query string
	*
	* @return string
	*
	* @throws Exception\InvalidMethod
	* @throws Exception\InvalidFormat
	* @throws Exception\InvalidResponse
	* @throws Exception\APIError
	* @throws \Veridu\Signature\Exception\NonceMismatch
	*/
	public function signedFetch($method, $resource, $data = null) {
		$sign = $this->signature->sign(
			$this->config->getClient(),
			$this->config->getSecret(),
			$this->config->getVersion(),
			$method,
			URL::build(self::BASE_URL, array($this->config->getVersion(), $resource))
		);
		if (empty($data))
			$json = $this->fetch($method, $resource, $sign);
		else {
			if (is_array($data))
				$data = Compat::buildQuery($data);
			$data = ltrim($data, '?&');
			$json = $this->fetch($method, $resource, "{$sign}&{$data}");
		}
		if ((empty($json['nonce'])) || (strcmp($json['nonce'], $this->signature->lastNonce()) != 0))
			throw new \Veridu\Signature\Exception\NonceMismatch;
		unset($json['nonce']);
		return $json;
	}

	/**
	* Sets the basic client configuration
	*
	* @param Config $config Basic client configuration
	*
	* @return void
	*/
	public function setConfig(Config &$config) {
		$this->config = $config;
		$this->http->setHeader('Veridu-Client', $this->config->getClient());
	}

	/**
	* Returns the current basic client configuration
	*
	* @return Config
	*/
	public function getConfig() {
		return $this->config;
	}

	/**
	* Sets the HTTP Client to perform API requests
	*
	* @param HTTPClient $http HTTP Client
	*
	* @return void
	*/
	public function setHTTP(HTTPClient &$http) {
		$this->http = $http;
		$this->http->setHeader('Veridu-Client', $this->config->getClient());
		if (!empty($this->session))
			$this->http->setHeader('Veridu-Session', $this->session);
		$this->http->setUserAgent('Veridu-PHP/' . Version::stringify());
	}

	/**
	* Returns the current HTTP Client
	*
	* @return HTTPClient
	*/
	public function getHTTP() {
		return $this->http;
	}

	/**
	* Sets the signature object for signed API requests
	*
	* @param Signature $signature Signature object
	*
	* @return void
	*/
	public function setSignature(Signature &$signature) {
		$this->signature = $signature;
	}

	/**
	* Returns the current signature object
	*
	* @return Signature
	*/
	public function getSignature() {
		return $this->signature;
	}

	/**
	* Sets the session token to be used on requests
	*
	* @param string $value Session token
	*
	* @return void
	*/
	public function setSession($value) {
		$this->session = $value;
		$this->http->setHeader('Veridu-Session', $value);
	}

	/**
	* Returns the session token
	*
	* @return string
	*/
	public function getSession() {
		return $this->session;
	}

	/**
	* Eliminates the session token
	*
	* @return void
	*/
	public function purgeSession() {
		$this->session = null;
		$this->http->unsetHeader('Veridu-Session');
	}

	/**
	* Returns the last API error
	*
	* @return string|null
	*/
	public function lastError() {
		return $this->lastError;
	}

}

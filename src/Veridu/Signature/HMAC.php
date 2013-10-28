<?php

namespace Veridu\Signature;

/**
*	HMAC signature implementation
*/
class HMAC extends AbstractSignature {
	private $hash;

	/**
	* @param string $client
	* @param string $secret
	* @param string $version
	* @param string $hash
	*
	* @return void
	*/
	public function __construct($client, $secret, $version, $hash = 'sha1') {
		parent::__construct($client, $secret, $version);
		$this->hash = $hash;
	}

	/**
	* @param string $value
	*
	* @return void
	*/
	public function setHash($value) {
		$this->hash = $value;
	}

	/**
	* @return string
	*/
	public function getHash() {
		return $this->hash;
	}

	/**
	* {@inheritDoc}
	*/
	public function sign($method, $resource) {
		$this->nonce = bin2hex(openssl_random_pseudo_bytes(10));
		$param = array(
			'client' => $this->client,
			'method' => $method,
			'nonce' => $this->nonce,
			'resource' => $resource,
			'timestamp' => time(),
			'version' => $this->version
		);
		ksort($param);
		$param['signature'] = hash_hmac($this->hash, http_build_query($param, '', '&', PHP_QUERY_RFC1738), $this->secret);
		return http_build_query($param, '', '&', PHP_QUERY_RFC1738);
	}

}
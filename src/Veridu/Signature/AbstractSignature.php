<?php

namespace Veridu\Signature;

/**
*	Abstract signature implementation
*/
abstract class AbstractSignature implements Signature {
	protected $nonce = null;
	protected $client;
	protected $secret;
	protected $version;

	/**
	* @param string $client
	* @param string $secret
	* @param string $version
	*
	* @return void
	*/
	public function __construct($client, $secret, $version) {
		$this->client = $client;
		$this->secret = $secret;
		$this->version = $version;
	}

	/**
	* @return string
	*/
	public function lastNonce() {
		return $this->nonce;
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
	public function setSecret($value) {
		$this->secret = $value;
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
	}

	/**
	* @return string
	*/
	public function getVersion() {
		return $this->version;
	}

}
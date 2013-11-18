<?php
/**
* HMAC signature implementation
*/

namespace Veridu\Signature;

class HMAC extends AbstractSignature {
	private $hash;

	/**
	* Class constructor
	*
	* @param string $hash Hash algorithm name
	*
	* @return void
	*/
	public function __construct($hash = 'sha1') {
		$this->hash = $hash;
	}

	/**
	* Sets the hashing algorithm to be used
	*
	* @param string $value Hash algorithm name
	*
	* @return void
	*/
	public function setHash($hash) {
		$this->hash = $hash;
	}

	/**
	* Returns the hashing algorithm name
	*
	* @return string
	*/
	public function getHash() {
		return $this->hash;
	}

	/**
	* {@inheritDoc}
	*/
	public function sign($client, $secret, $version, $method, $resource) {
		$this->nonce = bin2hex(openssl_random_pseudo_bytes(10));
		$param = array(
			'client' => $client,
			'method' => $method,
			'nonce' => $this->nonce,
			'resource' => $resource,
			'timestamp' => time(),
			'version' => $version
		);
		ksort($param);
		$param['signature'] = hash_hmac($this->hash, http_build_query($param, '', '&', PHP_QUERY_RFC1738), $secret);
		return http_build_query($param, '', '&', PHP_QUERY_RFC1738);
	}

}
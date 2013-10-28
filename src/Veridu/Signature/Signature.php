<?php

namespace Veridu\Signature;

/**
*	Signature interface
*/
interface Signature {
	/**
	* @param string $method
	* @param string $resource
	*
	* @return string
	*/
	public function sign($method, $resource);

	/**
	* @return string
	*/
	public function lastNonce();

	/**
	* @param string $value
	*
	* @return void
	*/
	public function setClient($value);

	/**
	* @return string
	*/
	public function getClient();

	/**
	* @param string $value
	*
	* @return void
	*/
	public function setSecret($value);

	/**
	* @return string
	*/
	public function getSecret();

	/**
	* @param string $value
	*
	* @return void
	*/
	public function setVersion($value);

	/**
	* @return string
	*/
	public function getVersion();

}
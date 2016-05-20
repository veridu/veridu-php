<?php
/**
* Endpoint interface definition
*/

namespace Veridu\API;

use Veridu\Common\Config;
use GuzzleHttp\ClientInterface;
use Veridu\Signature\SignatureInterface;
use Veridu\Storage\StorageInterface;

interface EndpointInterface {

	/**
	* Sets the basic client configuration
	*
	* @param Config $config Basic client configuration
	*
	* @return self
	*/
	// public function setConfig(Config &$config);

	/**
	* Returns the current basic client configuration
	*
	* @return Config
	*/
	// public function getConfig();

	/**
	* Sets the HTTP Client to perform API requests
	*
	* @param ClientInterface $client HTTP Client
	*
	* @return self
	*/
	public function setClient(ClientInterface &$client);

	/**
	* Returns the current HTTP Client
	*
	* @return ClientInterface
	*/
	public function getClient();

	/**
	* Sets the signature object for signed API requests
	*
	* @param SignatureInterface $signature Signature object
	*
	* @return self
	*/
	public function setSignature(SignatureInterface &$signature);

	/**
	* Returns the current signature object
	*
	* @return SignatureInterface
	*/
	public function getSignature();

	/**
	* Sets the data storage to be used on requests
	*
	* @param Storage $storage Storage instance
	*
	* @return self
	*/
	public function setStorage(StorageInterface &$storage);

	/**
	* Returns the data storage instance
	*
	* @return Storage
	*/
	public function getStorage();

	/**
	* Returns the last Endpoint error
	*
	* @return string|null
	*/
	public function lastError();

}

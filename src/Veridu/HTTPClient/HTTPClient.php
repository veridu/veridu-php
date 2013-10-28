<?php

namespace Veridu\HTTPClient;

/**
*	HTTP Client interface
*/
interface HTTPClient {

	/**
	* @param string $header
	* @param string $value
	*
	* @return void
	*/
	public function setHeader($header, $value);

	/**
	* @param string $header
	*
	* @return void
	*/
	public function unsetHeader($header);

	/**
	* @param string $value
	*
	* @return void
	*/
	public function setUserAgent($value);

	/**
	* @param string $url
	*
	* @return string
	*
	* @throws ClientFailed EmptyResponse
	*/
	public function GET($url);

	/**
	* @param string $url
	* @param string/array $data
	*
	* @return string
	*
	* @throws ClientFailed EmptyResponse
	*/
	public function POST($url, $data = null);

	/**
	* @param string $url
	* @param string/array $data
	*
	* @return string
	*
	* @throws ClientFailed EmptyResponse
	*/
	public function DELETE($url, $data = null);

	/**
	* @param string $url
	* @param string/array $data
	*
	* @return string
	*
	* @throws ClientFailed EmptyResponse
	*/
	public function PUT($url, $data = null);

}
<?php

namespace Veridu\HTTPClient;

/**
* Abstract Client implementation
*/
abstract class AbstractClient implements HTTPClient {
	protected $headers = array();
	protected $userAgent = 'StreamClient';

	/**
	* @return array
	*/
	protected function prepareHeaders() {
		$headers = array();
		foreach ($this->headers as $header => $value)
			$headers[] = "{$header}: {$value}";
		return $headers;
	}

	/**
	* {@inheritDoc}
	*/
	public function setHeader($header, $value) {
		$this->headers[$header] = $value;
	}

	/**
	* {@inheritDoc}
	*/
	public function unsetHeader($header) {
		unset($this->headers[$header]);
	}

	/**
	* {@inheritDoc}
	*/
	public function setUserAgent($value) {
		$this->userAgent = $value;
	}
}
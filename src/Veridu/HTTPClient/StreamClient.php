<?php

namespace Veridu\HTTPClient;

/**
* PHP Stream HTTP Client implementation
*/
class StreamClient extends AbstractClient {

	/**
	* Creates a request context
	*
	* @param string $method Request method
	* @param string $data Request payload
	*
	* @return resource
	*/
	private function createContext($method, $data = null) {
		$setup = array(
			'method' => $method,
			'header' => $this->prepareHeaders(),
			'user_agent' => $this->userAgent,
			'ignore_errors' => true
		);
		if (in_array($method, array('POST', 'PUT', 'DELETE'))) {
			$setup['header'][] = 'Content-Type: application/x-www-form-urlencoded';
			if (empty($data))
				$setup['header'][] = 'Content-Length: 0';
			else {
				$setup['header'][] = 'Content-Length: ' . strlen($data);
				$setup['content'] = $data;
			}
		}
		return stream_context_create(array(
			'http' => $setup
		));
	}

	/**
	* Performs a HTTP request
	*
	* @param string $method Request method
	* @param string $url Full URL to resource
	* @param string|array $data Request payload
	*
	* @return string Request response
	*
	* @throws ClientFailed
	* @throws EmptyResponse
	*/
	private function streamRequest($method, $url, $data = null) {
		if (is_array($data))
			$data = http_build_query($data, '', '&', PHP_QUERY_RFC1738);
		$stream = @fopen($url, 'r', false, $this->createContext($method, $data));
		if (!is_resource($stream))
			throw new Exception\ClientFailed;;
		$response = stream_get_contents($stream);
		fclose($stream);
		if (empty($response))
			throw new Exception\EmptyResponse;
		return $response;
	}

	/**
	* {@inheritDoc}
	*/
	public function GET($url) {
		return $this->streamRequest('GET', $url);
	}

	/**
	* {@inheritDoc}
	*/
	public function POST($url, $data = null) {
		return $this->streamRequest('POST', $url, $data);
	}

	/**
	* {@inheritDoc}
	*/
	public function DELETE($url, $data = null) {
		return $this->streamRequest('DELETE', $url, $data);
	}

	/**
	* {@inheritDoc}
	*/
	public function PUT($url, $data = null) {
		return $this->streamRequest('PUT', $url, $data);
	}

}
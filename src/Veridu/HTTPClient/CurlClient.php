<?php

namespace Veridu\HTTPClient;

/**
* cURL HTTP Client implementation
*/
class CurlClient extends AbstractClient {

	/**
	* @param string $method
	* @param string $url
	* @param string $data
	*
	* @return array
	*/
	private function createContext($method, $url, $data = null) {
		$opt = array(
			CURLOPT_AUTOREFERER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_MAXREDIRS => 5,
			CURLOPT_CONNECTTIMEOUT => 10,
			CURLOPT_TIMEOUT => 10,
			CURLOPT_URL => $url,
			CURLOPT_USERAGENT => $this->userAgent,
			CURLOPT_HTTPHEADER => $this->prepareHeaders()
		);
		switch ($method) {
			case 'GET':
				$opt[CURLOPT_HTTPGET] = true;
				break;
			case 'POST':
				$opt[CURLOPT_POST] = true;
				if (!empty($data))
					$opt[CURLOPT_POSTFIELDS] = $data;
				break;
			case 'DELETE':
				$opt[CURLOPT_CUSTOMREQUEST] = 'DELETE';
				if (!empty($data))
					$opt[CURLOPT_POSTFIELDS] = $data;
				break;
			case 'PUT':
				$opt[CURLOPT_PUT] = true;
				if (!empty($data))
					$opt[CURLOPT_POSTFIELDS] = $data;
				break;
		}
		return $opt;
	}

	/**
	* @param string $method
	* @param string $url
	* @param string/array $data
	*
	* @return string
	*
	* @throws ClientFailed EmptyResponse
	*/
	private function curlRequest($method, $url, $data = null) {
		if (is_array($data))
			$data = http_build_query($data, '', '&', PHP_QUERY_RFC1738);
		$handler = curl_init();
		if (!is_resource($handler))
			throw new Exception\ClientFailed;
		curl_setopt_array($handler, $this->createContext($method, $url, $data));
		$response = curl_exec($handler);
		curl_close($handler);
		if (empty($response))
			throw new Exception\EmptyResponse;
		return $response;
	}

	/**
	* @return void
	*
	* @throws ClientFailed
	*/
	public function __construct() {
		if (!function_exists('curl_version'))
			throw new Exception\ClientFailed('cURL is disabled');
	}

	/**
	* {@inheritDoc}
	*/
	public function GET($url) {
		return $this->curlRequest('GET', $url);
	}

	/**
	* {@inheritDoc}
	*/
	public function POST($url, $data = null) {
		return $this->curlRequest('POST', $url, $data);
	}

	/**
	* {@inheritDoc}
	*/
	public function DELETE($url, $data = null) {
		return $this->curlRequest('DELETE', $url, $data);
	}

	/**
	* {@inheritDoc}
	*/
	public function PUT($url, $data = null) {
		return $this->curlRequest('PUT', $url, $data);
	}

}
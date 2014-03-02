<?php
/**
* cURL HTTP Client implementation
*/

namespace Veridu\HTTPClient;

use Veridu\Common\Compat;

class CurlClient extends AbstractClient {
	/**
	* @var resource Stores a file handler for PUT requests
	*/
	private $putHandler = null;

	/**
	* Returns a list of cURL's options to be used as request context.
	*
	* @param string $method Request method
	* @param string $url Full URL to resource
	* @param string $data Request payload
	*
	* @return array cURL options
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
				if (empty($data))
					$opt[CURLOPT_POSTFIELDS] = null;
				else
					$opt[CURLOPT_POSTFIELDS] = $data;
				break;
			case 'DELETE':
				$opt[CURLOPT_CUSTOMREQUEST] = 'DELETE';
				if (empty($data))
					$opt[CURLOPT_POSTFIELDS] = null;
				else
					$opt[CURLOPT_POSTFIELDS] = $data;
				break;
			case 'PUT':
				$opt[CURLOPT_PUT] = true;
				if (!empty($data)) {
					$this->putHandler = fopen('php://memory', 'w');
					if (!is_resource($this->putHandler))
						throw new Exception\ClientFailed;
					fwrite($this->putHandler, $data);
					fseek($this->putHandler, 0);
					$opt[CURLOPT_INFILE] = $this->putHandler;
					$opt[CURLOPT_INFILESIZE] = strlen($data);
				}
				break;
		}
		return $opt;
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
	private function curlRequest($method, $url, $data = null) {
		if (is_array($data))
			$data = Compat::buildQuery($data);
		$handler = curl_init();
		if (!is_resource($handler))
			throw new Exception\ClientFailed;
		curl_setopt_array($handler, $this->createContext($method, $url, $data));
		$response = curl_exec($handler);
		$error = curl_errno($handler);
		curl_close($handler);
		if (($method === 'PUT') && (is_resource($this->putHandler)))
			fclose($this->putHandler);
		if ($error > 0)
			throw new Exception\ClientFailed;
		if (empty($response))
			throw new Exception\EmptyResponse;
		return $response;
	}

	/**
	* Class constructor
	*
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
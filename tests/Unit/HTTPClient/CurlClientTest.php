<?php

namespace VeriduTest\Unit\HTTPClient;

use Veridu\HTTPClient\CurlClient;

class CurlClientTest extends \PHPUnit_Framework_TestCase {

	protected $curl = null;

	protected function setUp() {
		$this->curl = new CurlClient;
	}

	public function testConstructCorrectInterface() {
		$this->assertInstanceOf('Veridu\\HTTPClient\HTTPClient', $this->curl);
	}

	public function testHeaders() {
		$this->curl->setHeader('Testing-Header', 'value');
		$response = $this->curl->GET('http://httpbin.org/get');
		$json = json_decode($response, true);
		$this->assertSame('value', $json['headers']['Testing-Header']);
	}

	public function testUserAgent() {
		$this->curl->setUserAgent('TestingUserAgent');
		$response = $this->curl->GET('http://httpbin.org/get');
		$json = json_decode($response, true);
		$this->assertSame('TestingUserAgent', $json['headers']['User-Agent']);
	}

	public function testValidGET() {
		$response = $this->curl->GET('http://httpbin.org/get?key=value');
		$json = json_decode($response, true);
		$this->assertSame('value', $json['args']['key']);
	}

	public function testInvalidGET() {
		$this->setExpectedException('Veridu\\HTTPClient\\Exception\\EmptyResponse');
		$this->curl->GET('http://example.invalid');
	}

	public function testValidPOSTWithOutData() {
		$response = $this->curl->POST('http://httpbin.org/post');
		$json = json_decode($response, true);
		$this->assertSame(array(), $json['form']);
	}

	public function testValidPOSTWithData() {
		$response = $this->curl->POST('http://httpbin.org/post', array('key' => 'value'));
		$json = json_decode($response, true);
		$this->assertSame('value', $json['form']['key']);
	}

	public function testInvalidPOST() {
		$this->setExpectedException('Veridu\\HTTPClient\\Exception\\EmptyResponse');
		$this->curl->POST('http://example.invalid');
	}

	public function testValidDELETEWithoutData() {
		$response = $this->curl->DELETE('http://httpbin.org/delete');
		$json = json_decode($response, true);
		$this->assertSame('', $json['data']);
	}

	public function testValidDELETEWithData() {
		$response = $this->curl->DELETE('http://httpbin.org/delete', array('key' => 'value'));
		$json = json_decode($response, true);
		$this->assertSame('key=value', $json['data']);
	}

	public function testInvalidDELETE() {
		$this->setExpectedException('Veridu\\HTTPClient\\Exception\\EmptyResponse');
		$this->curl->DELETE('http://example.invalid');
	}

	public function testValidPUTWithoutData() {
		$response = $this->curl->PUT('http://httpbin.org/put');
		$json = json_decode($response, true);
		$this->assertSame('', $json['data']);
	}

	public function testValidPUTWithData() {
		$response = $this->curl->PUT('http://httpbin.org/put', array('key' => 'value'));
		$json = json_decode($response, true);
		$this->assertSame('key=value', $json['data']);
	}

	public function testInvalidPUT() {
		$this->setExpectedException('Veridu\\HTTPClient\\Exception\\EmptyResponse');
		$this->curl->PUT('http://example.invalid');
	}
}
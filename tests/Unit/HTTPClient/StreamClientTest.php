<?php

namespace VeriduTest\Unit\HTTPClient;

use Veridu\HTTPClient\StreamClient;

class StreamClientTest extends \PHPUnit_Framework_TestCase {

	protected $stream = null;

	protected function setUp() {
		$this->stream = new StreamClient;
	}

	public function testConstructCorrectInterface() {
		$this->assertInstanceOf('Veridu\\HTTPClient\HTTPClient', $this->stream);
	}

	public function testHeaders() {
		$this->stream->setHeader('Testing-Header', 'value');
		$response = $this->stream->GET('http://httpbin.org/get');
		$json = json_decode($response, true);
		$this->assertSame('value', $json['headers']['Testing-Header']);
	}

	public function testUserAgent() {
		$this->stream->setUserAgent('TestingUserAgent');
		$response = $this->stream->GET('http://httpbin.org/get');
		$json = json_decode($response, true);
		$this->assertSame('TestingUserAgent', $json['headers']['User-Agent']);
	}

	public function testValidGET() {
		$response = $this->stream->GET('http://httpbin.org/get?key=value');
		$json = json_decode($response, true);
		$this->assertSame('value', $json['args']['key']);
	}

	public function testInvalidGET() {
		$this->setExpectedException('Veridu\\HTTPClient\\Exception\\ClientFailed');
		$this->stream->GET('http://example.invalid');
	}

	public function testValidPOSTWithOutData() {
		$response = $this->stream->POST('http://httpbin.org/post');
		$json = json_decode($response, true);
		$this->assertSame(array(), $json['form']);
	}

	public function testValidPOSTWithData() {
		$response = $this->stream->POST('http://httpbin.org/post', array('key' => 'value'));
		$json = json_decode($response, true);
		$this->assertSame('value', $json['form']['key']);
	}

	public function testInvalidPOST() {
		$this->setExpectedException('Veridu\\HTTPClient\\Exception\\ClientFailed');
		$this->stream->POST('http://example.invalid');
	}

	public function testValidDELETEWithoutData() {
		$response = $this->stream->DELETE('http://httpbin.org/delete');
		$json = json_decode($response, true);
		$this->assertSame('', $json['data']);
	}

	public function testValidDELETEWithData() {
		$response = $this->stream->DELETE('http://httpbin.org/delete', array('key' => 'value'));
		$json = json_decode($response, true);
		$this->assertSame('key=value', $json['data']);
	}

	public function testInvalidDELETE() {
		$this->setExpectedException('Veridu\\HTTPClient\\Exception\\ClientFailed');
		$this->stream->DELETE('http://example.invalid');
	}

	public function testValidPUTWithoutData() {
		$response = $this->stream->PUT('http://httpbin.org/put');
		$json = json_decode($response, true);
		$this->assertSame('', $json['data']);
	}

	public function testValidPUTWithData() {
		$response = $this->stream->PUT('http://httpbin.org/put', array('key' => 'value'));
		$json = json_decode($response, true);
		$this->assertSame('value', $json['form']['key']);
	}

	public function testInvalidPUT() {
		$this->setExpectedException('Veridu\\HTTPClient\\Exception\\ClientFailed');
		$this->stream->PUT('http://example.invalid');
	}

}
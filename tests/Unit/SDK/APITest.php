<?php

namespace VeriduTest\Unit\SDK;

use Veridu\Common\Config;
use Veridu\HTTPClient\CurlClient;
use Veridu\SDK\API;
use Veridu\Signature\HMAC;

class APITest extends \PHPUnit_Framework_TestCase {

	protected $api = null;

	protected function setUp() {
		$config = new Config(
			'client',
			'secret',
			'version'
		);
		$http = $this->getMockForAbstractClass('Veridu\\HTTPClient\\AbstractClient');
		$signature = $this->getMockForAbstractClass('Veridu\\Signature\\AbstractSignature');

		$this->api = new API($config, $http, $signature);
	}

	public function testInvalidMethodFetch() {
		$this->setExpectedException('Veridu\\SDK\\Exception\\InvalidMethod');
		$this->api->fetch('INVALID', '/testing');
		$this->assertNull($this->api->lastError());
	}

	public function testInvalidFormatFetch() {
		$http = $this->getMockForAbstractClass('Veridu\\HTTPClient\\AbstractClient');
		$http->expects($this->once())
			->method('get')
			->will($this->returnValue(''));
		$this->api->setHTTP($http);
		$this->setExpectedException('Veridu\\SDK\\Exception\\InvalidFormat');
		$this->api->fetch('GET', '/testing');
		$this->assertNull($this->api->lastError());
	}

	public function testInvalidResponseFetch() {
		$payload = json_encode(array(
			'test' => true
		));
		$http = $this->getMockForAbstractClass('Veridu\\HTTPClient\\AbstractClient');
		$http->expects($this->once())
			->method('get')
			->will($this->returnValue($payload));
		$this->api->setHTTP($http);
		$this->setExpectedException('Veridu\\SDK\\Exception\\InvalidResponse');
		$this->api->fetch('GET', '/testing');
		$this->assertNull($this->api->lastError());
	}

	public function testAPIErrorFetch() {
		$payload = json_encode(array(
			'status' => false,
			'error' => array(
				'type' => 'TEST',
				'message' => 'Testing'
			)
		));
		$http = $this->getMockForAbstractClass('Veridu\\HTTPClient\\AbstractClient');
		$http->expects($this->once())
			->method('get')
			->will($this->returnValue($payload));
		$this->api->setHTTP($http);
		$this->setExpectedException('Veridu\\SDK\\Exception\\APIError');
		$this->api->fetch('GET', '/testing');
		$this->assertSame('TEST', $this->api->lastError());
	}

	public function testFetchGETWithoutData() {
		$http = $this->getMockForAbstractClass('Veridu\\HTTPClient\\AbstractClient');
		$http->expects($this->once())
			->method('get')
			->will($this->returnCallback(function ($url) {
				return json_encode(array(
					'status' => true,
					'url' => $url
				));
			}));
		$this->api->setHTTP($http);
		$response = $this->api->fetch('GET', '/testing');
		$this->assertSame('https://api.veridu.com/version/testing', $response['url']);
		$this->assertNull($this->api->lastError());
	}

	public function testFetchGETWithData() {
		$http = $this->getMockForAbstractClass('Veridu\\HTTPClient\\AbstractClient');
		$http->expects($this->once())
			->method('get')
			->will($this->returnCallback(function ($url) {
				return json_encode(array(
					'status' => true,
					'url' => $url
				));
			}));
		$this->api->setHTTP($http);
		$response = $this->api->fetch('GET', '/testing', 'key=value');
		$this->assertSame('https://api.veridu.com/version/testing?key=value', $response['url']);
		$this->assertNull($this->api->lastError());
	}

	public function testFetchPOSTWithoutData() {
		$http = $this->getMockForAbstractClass('Veridu\\HTTPClient\\AbstractClient');
		$http->expects($this->once())
			->method('post')
			->will($this->returnCallback(function ($url, $data) {
				return json_encode(array(
					'status' => true,
					'url' => $url,
					'data' => $data
				));
			}));
		$this->api->setHTTP($http);
		$response = $this->api->fetch('POST', '/testing');
		$this->assertSame('https://api.veridu.com/version/testing', $response['url']);
		$this->assertNull($response['data']);
		$this->assertNull($this->api->lastError());
	}

	public function testFetchPOSTWithData() {
		$http = $this->getMockForAbstractClass('Veridu\\HTTPClient\\AbstractClient');
		$http->expects($this->once())
			->method('post')
			->will($this->returnCallback(function ($url, $data) {
				return json_encode(array(
					'status' => true,
					'url' => $url,
					'data' => $data
				));
			}));
		$this->api->setHTTP($http);
		$response = $this->api->fetch('POST', '/testing', 'key=value');
		$this->assertSame('https://api.veridu.com/version/testing', $response['url']);
		$this->assertSame('key=value', $response['data']);
		$this->assertNull($this->api->lastError());
	}

	public function testFetchDELETEWithoutData() {
		$http = $this->getMockForAbstractClass('Veridu\\HTTPClient\\AbstractClient');
		$http->expects($this->once())
			->method('delete')
			->will($this->returnCallback(function ($url, $data) {
				return json_encode(array(
					'status' => true,
					'url' => $url,
					'data' => $data
				));
			}));
		$this->api->setHTTP($http);
		$response = $this->api->fetch('DELETE', '/testing');
		$this->assertSame('https://api.veridu.com/version/testing', $response['url']);
		$this->assertNull($response['data']);
		$this->assertNull($this->api->lastError());
	}

	public function testFetchDELETEWithData() {
		$http = $this->getMockForAbstractClass('Veridu\\HTTPClient\\AbstractClient');
		$http->expects($this->once())
			->method('delete')
			->will($this->returnCallback(function ($url, $data) {
				return json_encode(array(
					'status' => true,
					'url' => $url,
					'data' => $data
				));
			}));
		$this->api->setHTTP($http);
		$response = $this->api->fetch('DELETE', '/testing', 'key=value');
		$this->assertSame('https://api.veridu.com/version/testing', $response['url']);
		$this->assertSame('key=value', $response['data']);
		$this->assertNull($this->api->lastError());
	}

	public function testFetchPUTWithoutData() {
		$http = $this->getMockForAbstractClass('Veridu\\HTTPClient\\AbstractClient');
		$http->expects($this->once())
			->method('put')
			->will($this->returnCallback(function ($url, $data) {
				return json_encode(array(
					'status' => true,
					'url' => $url,
					'data' => $data
				));
			}));
		$this->api->setHTTP($http);
		$response = $this->api->fetch('PUT', '/testing');
		$this->assertSame('https://api.veridu.com/version/testing', $response['url']);
		$this->assertNull($response['data']);
		$this->assertNull($this->api->lastError());
	}

	public function testFetchPUTWithData() {
		$http = $this->getMockForAbstractClass('Veridu\\HTTPClient\\AbstractClient');
		$http->expects($this->once())
			->method('put')
			->will($this->returnCallback(function ($url, $data) {
				return json_encode(array(
					'status' => true,
					'url' => $url,
					'data' => $data
				));
			}));
		$this->api->setHTTP($http);
		$response = $this->api->fetch('PUT', '/testing', 'key=value');
		$this->assertSame('https://api.veridu.com/version/testing', $response['url']);
		$this->assertSame('key=value', $response['data']);
		$this->assertNull($this->api->lastError());
	}

	public function testNonceMismatchSignedFetch() {
		$payload = json_encode(array(
			'status' => true,
			'key' => 'value'
		));
		$http = $this->getMockForAbstractClass('Veridu\\HTTPClient\\AbstractClient');
		$http->expects($this->once())
			->method('get')
			->will($this->returnValue($payload));
		$this->api->setHTTP($http);
		$this->api->setSignature(new HMAC);
		$this->setExpectedException('Veridu\\Signature\\Exception\\NonceMismatch');
		$this->api->signedFetch('GET', '/testing');
		$this->assertNull($this->api->lastError());
	}

	public function testSignedFetchWithoutData() {
		$http = $this->getMockForAbstractClass('Veridu\\HTTPClient\\AbstractClient');
		$http->expects($this->once())
			->method('get')
			->will($this->returnCallback(function ($url) {
				preg_match('/&nonce=([^&]+)&/', $url, $matches);
				return json_encode(array(
					'status' => true,
					'nonce' => $matches[1]
				));
			}));
		$this->api->setHTTP($http);
		$this->api->setSignature(new HMAC);
		$response = $this->api->signedFetch('GET', '/testing');
		$this->assertTrue($response['status']);
		$this->assertNull($this->api->lastError());
	}

	public function testSignedFetchGETWithStringData() {
		$http = $this->getMockForAbstractClass('Veridu\\HTTPClient\\AbstractClient');
		$http->expects($this->once())
			->method('get')
			->will($this->returnCallback(function ($url) {
				preg_match('/&nonce=([^&]+)&/', $url, $matches);
				return json_encode(array(
					'status' => true,
					'nonce' => $matches[1],
					'data' => (strpos($url, 'key=value') !== false)
				));
			}));
		$this->api->setHTTP($http);
		$this->api->setSignature(new HMAC);
		$response = $this->api->signedFetch('GET', '/testing', 'key=value');
		$this->assertTrue($response['data']);
		$this->assertNull($this->api->lastError());
	}

	public function testSignedFetchGETWithStringDataLeadingQuestionMark() {
		$http = $this->getMockForAbstractClass('Veridu\\HTTPClient\\AbstractClient');
		$http->expects($this->once())
			->method('get')
			->will($this->returnCallback(function ($url) {
				preg_match('/&nonce=([^&]+)&/', $url, $matches);
				return json_encode(array(
					'status' => true,
					'nonce' => $matches[1],
					'data' => (strpos($url, 'key=value') !== false)
				));
			}));
		$this->api->setHTTP($http);
		$this->api->setSignature(new HMAC);
		$response = $this->api->signedFetch('GET', '/testing', '?key=value');
		$this->assertTrue($response['data']);
		$this->assertNull($this->api->lastError());
	}

	public function testSignedFetchGETWithStringDataLeadingAnd() {
		$http = $this->getMockForAbstractClass('Veridu\\HTTPClient\\AbstractClient');
		$http->expects($this->once())
			->method('get')
			->will($this->returnCallback(function ($url) {
				preg_match('/&nonce=([^&]+)&/', $url, $matches);
				return json_encode(array(
					'status' => true,
					'nonce' => $matches[1],
					'data' => (strpos($url, 'key=value') !== false)
				));
			}));
		$this->api->setHTTP($http);
		$this->api->setSignature(new HMAC);
		$response = $this->api->signedFetch('GET', '/testing', '&key=value');
		$this->assertTrue($response['data']);
		$this->assertNull($this->api->lastError());
	}

	public function testSignedFetchGETWithArrayData() {
		$http = $this->getMockForAbstractClass('Veridu\\HTTPClient\\AbstractClient');
		$http->expects($this->once())
			->method('get')
			->will($this->returnCallback(function ($url) {
				preg_match('/&nonce=([^&]+)&/', $url, $matches);
				return json_encode(array(
					'status' => true,
					'nonce' => $matches[1],
					'data' => (strpos($url, 'key=value') !== false)
				));
			}));
		$this->api->setHTTP($http);
		$this->api->setSignature(new HMAC);
		$response = $this->api->signedFetch('GET', '/testing', array('key' => 'value'));
		$this->assertTrue($response['data']);
		$this->assertNull($this->api->lastError());
	}

	public function testSignedFetchPOSTWithStringData() {
		$http = $this->getMockForAbstractClass('Veridu\\HTTPClient\\AbstractClient');
		$http->expects($this->once())
			->method('post')
			->will($this->returnCallback(function ($url, $data) {
				preg_match('/&nonce=([^&]+)&/', $data, $matches);
				return json_encode(array(
					'status' => true,
					'nonce' => $matches[1],
					'data' => (strpos($data, 'key=value') !== false)
				));
			}));
		$this->api->setHTTP($http);
		$this->api->setSignature(new HMAC);
		$response = $this->api->signedFetch('POST', '/testing', 'key=value');
		$this->assertTrue($response['data']);
		$this->assertNull($this->api->lastError());
	}

	public function testSignedFetchDELETEWithStringData() {
		$http = $this->getMockForAbstractClass('Veridu\\HTTPClient\\AbstractClient');
		$http->expects($this->once())
			->method('delete')
			->will($this->returnCallback(function ($url, $data) {
				preg_match('/&nonce=([^&]+)&/', $data, $matches);
				return json_encode(array(
					'status' => true,
					'nonce' => $matches[1],
					'data' => (strpos($data, 'key=value') !== false)
				));
			}));
		$this->api->setHTTP($http);
		$this->api->setSignature(new HMAC);
		$response = $this->api->signedFetch('DELETE', '/testing', 'key=value');
		$this->assertTrue($response['data']);
		$this->assertNull($this->api->lastError());
	}

	public function testSignedFetchPUTWithStringData() {
		$http = $this->getMockForAbstractClass('Veridu\\HTTPClient\\AbstractClient');
		$http->expects($this->once())
			->method('put')
			->will($this->returnCallback(function ($url, $data) {
				preg_match('/&nonce=([^&]+)&/', $data, $matches);
				return json_encode(array(
					'status' => true,
					'nonce' => $matches[1],
					'data' => (strpos($data, 'key=value') !== false)
				));
			}));
		$this->api->setHTTP($http);
		$this->api->setSignature(new HMAC);
		$response = $this->api->signedFetch('PUT', '/testing', 'key=value');
		$this->assertTrue($response['data']);
		$this->assertNull($this->api->lastError());
	}

	public function testSetAndGetConfig() {
		$config = new Config(
			'testing-client',
			'testing-secret',
			'testing-version'
		);
		$this->api->setConfig($config);
		$this->assertSame($config, $this->api->getConfig());
	}

	public function testSetAndGetHTTPWithSession() {
		$this->api->setSession('session');
		$http = new CurlClient;
		$this->api->setHTTP($http);
		$this->assertSame($http, $this->api->getHTTP());
	}

	public function testSetAndGetHTTPWithoutSession() {
		$http = new CurlClient;
		$this->api->setHTTP($http);
		$this->assertSame($http, $this->api->getHTTP());
	}

	public function testSetAndGetSignature() {
		$signature = new HMAC;
		$this->api->setSignature($signature);
		$this->assertSame($signature, $this->api->getSignature());
	}

	public function testSetAndGetSession() {
		$this->assertNull($this->api->getSession());
		$this->api->setSession('session');
		$this->assertSame('session', $this->api->getSession());
	}

	public function testPurgeSession() {
		$this->api->setSession('session');
		$this->api->purgeSession();
		$this->assertNull($this->api->getSession());
	}

	public function testLastError() {
		$this->assertNull($this->api->lastError());
	}

}
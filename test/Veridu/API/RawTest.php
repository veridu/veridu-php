<?php

namespace VeriduTest;

use Veridu\API\Raw;
use Veridu\Storage\MemoryStorage;
use Veridu\Exception;
use Veridu\HTTPClient;

class RawTest extends \PHPUnit_Framework_TestCase {

	protected function setUp () {
		$this->config = [
			'key' => 'key',
			'secret' => 'secret',
			'version' => 'version'
		];
		$this->client = $this
			->getMockBuilder('\GuzzleHttp\Client')
			->getMock();
		$response = $this
			->getMockBuilder('\GuzzleHttp\Psr7\Response')
			->getMock();
		$this->signature = $this
			->getMockBuilder('Veridu\Signature\SignatureInterface')
			->getMock();
		$this->storage = $this
			->getMockBuilder('Veridu\Storage\StorageInterface')
			->setMethods(['isUsernameEmpty', 'getUsername', 'setSessionToken', 'getSessionToken', 'setSessionExpires', 'getSessionExpires', 'purgeSession', 'setUsername', 'isSessionEmpty', 'purgeUsername'])
			->getMock();
		$this->raw = $this
			->getMockBuilder('Veridu\API\Raw')
			->setConstructorArgs([$this->config['key'], $this->config['secret'], $this->config['version'], &$this->client, &$this->signature, &$this->storage])
			->setMethods(['setKey', 'setSecret', 'setVersion', 'signedFetch', 'fetch'])
			->getMock();
	}

	public function testRetrieveDataReturnsArray () {
		$this
			->storage
			->method('getUsername')
			->will($this->returnValue('username'));
		$this
			->raw
			->method('signedFetch')
			->will($this->returnValue(
				[
					'data' => [
						'test' => 'test'
					]
				]
			));
		$this->assertSame(['test' => 'test'], $this->raw->retrieveData('username', 'provider'));
	}

	public function testRetrieveDataThrowsEmptySession () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));
		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->raw->retrieveData('username', 'provider');
	}

	public function testRetrieveDataThrowsInvalidUsername () {
		$this->setExpectedException('Veridu\API\Exception\InvalidUsername');
		$this->raw->retrieveData('@123#');
	}

	public function testRetrieveCredentialReturnsArray () {
		$this
			->storage
			->method('getUsername')
			->will($this->returnValue('username'));
		$this
			->raw
			->method('signedFetch')
			->will($this->returnValue(
				[
					'credential' => [
						'test' => 'test'
					]
				]
			));
		$this->assertSame(['test' => 'test'], $this->raw->retrieveCredentials('username', 'provider'));
	}

	public function testRetrieveCredentialThrowsEmptySession () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));
		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->raw->retrieveCredentials('username', 'provider');
	}

	public function testRetrieveCredentialThrowsInvalidUsername () {
		$this->setExpectedException('Veridu\API\Exception\InvalidUsername');
		$this->raw->retrieveCredentials('@123#');
	}
}
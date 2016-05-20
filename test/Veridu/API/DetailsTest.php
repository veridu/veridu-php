<?php

namespace VeriduTest;

use Veridu\API\Details;
use Veridu\Storage\MemoryStorage;
use Veridu\Exception;
use Veridu\HTTPClient;

class DetailsTest extends \PHPUnit_Framework_TestCase {

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
		$this->details = $this
			->getMockBuilder('Veridu\API\Details')
			->setConstructorArgs([$this->config['key'], $this->config['secret'], $this->config['version'], &$this->client, &$this->signature, &$this->storage])
			->setMethods(['setKey', 'setSecret', 'setVersion', 'signedFetch', 'fetch'])
			->getMock();
	}

	public function testRetrieveReturnsArray () {
		$this
			->storage
			->method('getUsername')
			->will($this->returnValue('username'));
		$this
			->details
			->method('fetch')
			->will($this->returnValue(
				[
					'list' => [
						'test' => 'test'
					]
				]
			));
		$this->assertSame(['test' => 'test'], $this->details->retrieve('username'));
	}

	public function testRetrieveThrowsEmptySession () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));
		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->details->retrieve('username');
	}

	public function testRetrieveThrowsEmptyUsername () {
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));
		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->details->retrieve();
	}

	public function testRetrieveThrowsInvalidUsername () {
		$this->setExpectedException('Veridu\API\Exception\InvalidUsername');
		$this->details->retrieve('@123#');
	}

}

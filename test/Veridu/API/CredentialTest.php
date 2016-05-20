<?php

namespace VeriduTest;

use Veridu\API\Credential;

class CredentialTest extends \PHPUnit_Framework_TestCase {
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
		$this->credential = $this
			->getMockBuilder('Veridu\API\Credential')
			->setConstructorArgs([$this->config['key'], $this->config['secret'], $this->config['version'], &$this->client, &$this->signature, &$this->storage])
			->setMethods(['setKey', 'setSecret', 'setVersion', 'fetch'])
			->getMock();
	}

	public function testDetailsThrowsEmptySession () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));
		$method = new \ReflectionMethod('Veridu\API\Credential', 'details');
		$method->setAccessible(true);
		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$result = $method->invoke($this->credential);

	}

	public function testDetailsReturnsArray () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->credential
			->method('fetch')
			->will($this->returnValue(['test' => 'test']));

		$this->assertSame(['test' => 'test'], $this->credential->details());
	}
}

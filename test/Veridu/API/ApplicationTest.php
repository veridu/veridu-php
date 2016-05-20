<?php

namespace VeriduTest;

use Veridu\API\Application;
use Veridu\Storage\MemoryStorage;
use Veridu\Exception;
use Veridu\HTTPClient;

class ApplicationTest extends \PHPUnit_Framework_TestCase {

	protected function setUp () {
		$this->config = [
			'key' => 'key',
			'secret' => 'secret',
			'version' => 'version'
		];
		$this->client = $this
			->getMockBuilder('\GuzzleHttp\Client')
			->getMock();
		$this->signature = $this
			->getMockBuilder('Veridu\Signature\SignatureInterface')
			->getMock();
		$this->storage = $this
			->getMockBuilder('Veridu\Storage\StorageInterface')
			->setMethods(['isUsernameEmpty', 'getUsername', 'setSessionToken', 'getSessionToken', 'setSessionExpires', 'getSessionExpires', 'purgeSession', 'setUsername', 'isSessionEmpty', 'purgeUsername'])
			->getMock();
		$this->application = $this
			->getMockBuilder('Veridu\API\Application')
			->setConstructorArgs([$this->config['key'], $this->config['secret'], $this->config['version'], &$this->client, &$this->signature, &$this->storage])
			->setMethods(['setKey', 'setSecret', 'setVersion', 'fetch', 'signedFetch'])
			->getMock();
	}

	public function testCreateReturnsSelf() {
		$this
			->storage
			->method('getUsername')
			->will($this->returnValue('username'));
		$this
			->application
			->method('signedFetch')
			->will($this->returnValue($this->application));
		$this->assertSame($this->application, $this->application->create('provider', 'key', 'secret'));
	}

	public function testCreateThrowsEmptySession() {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->application->create('provider', 'key', 'secret');
	}

	public function testCreateThrowsEmptyUsername() {
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));
		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->application->create('provider', 'key', 'secret');
	}

	public function testListAllReturnsArray() {
		$this
			->storage
			->method('getUsername')
			->will($this->returnValue('username'));
		$this
			->application
			->method('signedFetch')
			->will($this->returnValue(
				[
					'list' =>
					[
						'response' => 'response'
					]
				]
			));
		$this->assertSame(['response'=>'response'], $this->application->listAll());
	}

	public function testListAllThrowsEmptySession() {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->application->listAll();
	}

	public function testListAllThrowsEmptyUsername() {
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));
		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->application->listAll();
	}

	public function testDetailsReturnsArray() {
		$this
			->storage
			->method('getUsername')
			->will($this->returnValue('username'));
		$this
			->application
			->method('signedFetch')
			->will($this->returnValue(
				[
					'response' => 'response'
				]
			));
		$this->assertSame(['response'=>'response'], $this->application->details('appId'));
	}

	public function testDetailsThrowsEmptySession() {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->application->details('appId');
	}

	public function testDetailsThrowsEmptyUsername() {
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));
		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->application->details('appId');
	}

	public function testEnableReturnsSelf() {
		$this
			->storage
			->method('getUsername')
			->will($this->returnValue('username'));
		$this
			->application
			->method('signedFetch')
			->will($this->returnValue($this->application));
		$this->assertSame($this->application, $this->application->enable('appId'));
	}

	public function testEnableThrowsEmptySession() {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));
		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->application->enable('appId');
	}

	public function testEnableThrowsEmptyUsername() {
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));
		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->application->enable('appId');
	}

	public function testDisableReturnsSelf() {
		$this
			->storage
			->method('getUsername')
			->will($this->returnValue('username'));
		$this
			->application
			->method('signedFetch')
			->will($this->returnValue($this->application));
		$this->assertSame($this->application, $this->application->disable('appId'));
	}

	public function testDisableThrowsEmptySession() {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));
		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->application->disable('appId');
	}

	public function testDisableThrowsEmptyUsername() {
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));
		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->application->disable('appId');
	}

}
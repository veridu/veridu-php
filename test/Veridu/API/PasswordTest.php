<?php

namespace VeriduTest;

use Veridu\API\Password;
use Veridu\Storage\MemoryStorage;
use Veridu\Exception;
use Veridu\HTTPClient;

class PasswordTest extends \PHPUnit_Framework_TestCase {

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
		$this->password = $this
			->getMockBuilder('Veridu\API\Password')
			->setConstructorArgs([$this->config['key'], $this->config['secret'], $this->config['version'], &$this->client, &$this->signature, &$this->storage])
			->setMethods(['setKey', 'setSecret', 'setVersion', 'signedFetch', 'fetch'])
			->getMock();
	}

	public function testSignupReturnsSelf () {
		$this
			->storage
			->method('getUsername')
			->will($this->returnValue('username'));
		$this
			->password
			->method('signedFetch')
			->will($this->returnValue($this->password));
		$this->assertSame($this->password, $this->password->signup('fname', 'lname', 'email', 'password'));
	}

	public function testSignupThrowsEmptySession () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));
		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->password->signup('fname', 'lname', 'email', 'password');
	}

	public function testSignupThrowsEmptyUsername () {
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));
		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->password->signup('fname', 'lname', 'email', 'password');
	}

	public function testLoginReturnsArray () {
		$this
			->storage
			->method('getUsername')
			->will($this->returnValue('username'));
		$this
			->password
			->method('signedFetch')
			->will($this->returnValue(
				[
					'test' => 'test'
				]
			));
		$this->assertSame(['test' => 'test'], $this->password->login('email', 'password'));
	}

	public function testLoginThrowsEmptySession () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));
		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->password->login('email', 'password');
	}

	public function testLoginThrowsEmptyUsername () {
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));
		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->password->login('email', 'password');
	}

	public function testRecoverReturnsSelf () {
		$this
			->storage
			->method('getUsername')
			->will($this->returnValue('username'));
		$this
			->password
			->method('signedFetch')
			->will($this->returnValue($this->password));
		$this->assertSame($this->password, $this->password->recover('email', 'callbackURL'));
	}

	public function testRecoverThrowsEmptySession () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));
		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->password->recover('email', 'callbackURL');
	}

	public function testRecoverThrowsEmptyUsername () {
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));
		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->password->recover('email', 'callbackURL');
	}

	public function testResetReturnsArray () {
		$this
			->storage
			->method('getUsername')
			->will($this->returnValue('username'));
		$this
			->password
			->method('signedFetch')
			->will($this->returnValue(
				[
					'test' => 'test'
				]
			));
		$this->assertSame(['test' => 'test'], $this->password->reset('recoverHash', 'password'));
	}

	public function testResetThrowsEmptySession () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));
		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->password->reset('recoverHash', 'password');
	}

	public function testResetThrowsEmptyUsername () {
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));
		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->password->reset('recoverHash', 'password');
	}

}
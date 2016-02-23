<?php

namespace VeriduTest;

use Veridu\API\State;

class StateTest extends \PHPUnit_Framework_TestCase {
	public function setUp () {
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
		$this->state = $this
			->getMockBuilder('Veridu\API\State')
			->setConstructorArgs([$this->config['key'], $this->config['secret'], $this->config['version'], &$this->client, &$this->signature, &$this->storage])
			->setMethods(['setKey', 'setSecret', 'setVersion', 'setSignature', 'fetch'])
			->getMock();
	}

	public function testRetrieveValidUsername () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(false));
		$this
			->state
			->method('fetch')
			->will($this->returnValue(['state'=>'test']));

		$this->assertSame('test', $this->state->retrieve('username'));
	}

	public function testRetrieveEmptySession () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->state->retrieve('username');
	}

	public function testRetrieveEmptyUsernameIsUsernameEmptyFalse () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('getUsername')
			->will($this->returnValue('username'));
		$this
			->state
			->method('fetch')
			->will($this->returnValue(['state' => 'test']));

		$this->assertSame('test', $this->state->retrieve());
	}

	public function testRetrieveEmptyUsernameIsUsernameEmpty () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->state->retrieve();
	}

	public function testRetrieveInvalidUsername () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));

		$this->setExpectedException('Veridu\API\Exception\InvalidUsername');
		$this->state->retrieve('@3123$');
	}


}

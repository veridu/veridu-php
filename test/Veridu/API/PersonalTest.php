<?php

namespace VeriduTest;

use Veridu\API\Personal;
use Veridu\Storage\MemoryStorage;
use Veridu\Exception;
use Veridu\HTTPClient;

class PersonalTest extends \PHPUnit_Framework_TestCase {

	protected function setUp () {
		$this->config = [
			'key' => 'key',
			'secret' => 'secret',
			'version' => 'version'
		];
		$this->client = $this
			->getMockBuilder('\GuzzleHttp\Client')
			->setMethods(['get', 'post', 'delete', 'put'])
			->getMock();
		$this->response = $this
			->getMockBuilder('\GuzzleHttp\Psr7\Response')
			->getMock();
		$this->signature = $this
			->getMockBuilder('Veridu\Signature\SignatureInterface')
			->getMock();
		$this->storage = $this
			->getMockBuilder('Veridu\Storage\StorageInterface')
			->setMethods(['isUsernameEmpty', 'getUsername', 'setSessionToken', 'getSessionToken', 'setSessionExpires', 'getSessionExpires', 'purgeSession', 'setUsername', 'isSessionEmpty', 'purgeUsername'])
			->getMock();
		$this->personal = $this
			->getMockBuilder('Veridu\API\Personal')
			->setConstructorArgs([$this->config['key'], $this->config['secret'], $this->config['version'], &$this->client, &$this->signature, &$this->storage])
			->setMethods(['setKey', 'setSecret', 'setVersion', 'signedFetch', 'fetch'])
			->getMock();
	}

	public function testCreateReturnsArray() {
		$this
			->storage
			->method('getUsername')
			->will($this->returnValue('username'));
		$this
			->personal
			->method('fetch')
			->will($this->returnValue(['fields' => 'fields']));

		$this->assertSame('fields', $this->personal->create(['data'=> 'data']));
	}

	public function testCreateThrowsEmptySession () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));
		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->personal->create(['data'=> 'data']);
	}

	public function testCreateThrowsEmptyUsername () {
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));
		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->personal->create(['data'=> 'data']);
	}

	public function testDetailsReturnsArray() {
		$this
			->storage
			->method('getUsername')
			->will($this->returnValue('username'));
		$this
			->personal
			->method('fetch')
			->will($this->returnValue(['state' => 'fields']));

		$this->assertSame('fields', $this->personal->details('state', 'username'));
	}


	public function testDetailsThrowsEmptySession() {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));
		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->personal->details('filter_state', 'username');
	}

	public function testDetailsThrowsEmptyUsername() {
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));
		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->personal->details('filter_state');
	}

	public function testDetailsThrowsInvalidUsername() {
		$this->setExpectedException('Veridu\API\Exception\InvalidUsername');
		$this->personal->details('filter_state', '@123#');
	}

	public function testUpdateReturnsArray() {
		$this
			->storage
			->method('getUsername')
			->will($this->returnValue('username'));
		$this
			->personal
			->method('fetch')
			->will($this->returnValue(['fields' => 'fields']));

		$this->assertSame('fields', $this->personal->update(['data'=> 'data']));
	}

	public function testUpdateThrowsEmptySession() {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));
		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->personal->update(['data'=> 'data']);
	}

	public function testUpdateThrowsEmptyUsername() {
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));
		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->personal->update(['data'=> 'data']);
	}

}

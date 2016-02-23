<?php

namespace VeriduTest;

use Veridu\API\Session;

class SessionTest extends \PHPUnit_Framework_TestCase {

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
		$this->session = $this
			->getMockBuilder('Veridu\API\Session')
			->setConstructorArgs([$this->config['key'], $this->config['secret'], $this->config['version'], &$this->client, &$this->signature, &$this->storage])
			->setMethods(['setKey', 'setSecret', 'setVersion', 'signedFetch'])
			->getMock();
	}

	public function testCreateReadonlyTrue () {
		$this->storage = new \Veridu\Storage\MemoryStorage();

		$this->session = $this
			->getMockBuilder('Veridu\API\Session')
			->setConstructorArgs([$this->config['key'], $this->config['secret'], $this->config['version'], &$this->client, &$this->signature, &$this->storage])
			->setMethods(['setKey', 'setSecret', 'setVersion', 'signedFetch'])
			->getMock();

		$this
			->session
			->method('signedFetch')
			->will($this->returnValue([
				'token' => 'test',
				'expires' => '2h'
				]));

		$this->assertSame('test', $this->session->create(true));
		$this->assertSame('test', $this->storage->getSessionToken());
		$this->assertEquals(2, $this->storage->getSessionExpires());
	}

	public function testCreateReadonlyFalse () {
		$this
			->session
			->method('signedFetch')
			->will($this->returnValue([
				'token' => 'test-readonly-false',
				'expires' => 'never'
				]));

		$this->assertSame('test-readonly-false', $this->session->create(false));
	}

	public function testExtend () {
		$this->storage = new \Veridu\Storage\MemoryStorage();
		$this->storage->setSessionToken('token');

		$this->session = $this
			->getMockBuilder('Veridu\API\Session')
			->setConstructorArgs([$this->config['key'], $this->config['secret'], $this->config['version'], &$this->client, &$this->signature, &$this->storage])
			->setMethods(['setKey', 'setSecret', 'setVersion', 'signedFetch'])
			->getMock();
			
		$this
			->session
			->method('signedFetch')
			->will($this->returnValue([
				'token' => 'test',
				'expires' => '2h'
				]));

		$this->assertSame($this->session, $this->session->extend());
		$this->assertEquals(2, $this->storage->getSessionExpires());
	}

	public function testExtendSessionEmpty () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->session->extend();
	}

	public function testExpire () {
		$this->storage = new \Veridu\Storage\MemoryStorage();

		$this->storage->setSessionToken('token');
		$this->session = $this
			->getMockBuilder('Veridu\API\Session')
			->setConstructorArgs([$this->config['key'], $this->config['secret'], $this->config['version'], &$this->client, &$this->signature, &$this->storage])
			->setMethods(['setKey', 'setSecret', 'setVersion', 'signedFetch'])
			->getMock();

		$this
			->session
			->method('signedFetch')
			->will($this->returnValue('test'));

		$this->assertSame($this->session, $this->session->expire());
		$this->assertEquals(-1, $this->storage->getSessionExpires());
		$this->assertNull($this->storage->getSessionToken());
	}

	public function testExpireEmptySession () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->session->expire();
	}
}

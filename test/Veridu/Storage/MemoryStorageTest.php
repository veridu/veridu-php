<?php

namespace VeriduTest\Signature;

use Veridu\Storage\MemoryStorage;

class MemoryStorageTest extends \PHPUnit_Framework_TestCase {

	protected $storage = null;

	protected function setUp() {
		$this->storage = new MemoryStorage();
	}

	public function testGetSessionTokenReturnsValidToken() {
		$this->storage->setSessionToken('token');
		$this->assertSame('token', $this->storage->getSessiontoken());
	}

	public function testGetSessionExpiresReturnsValidValue() {
		$this->storage->setSessionExpires(3);
		$this->assertEquals(3, $this->storage->getSessionExpires());
	}

	public function testPurgeSession() {
		$this->storage->setSessionToken('token');
		$this->storage->setSessionExpires(5);
		$this->assertSame('token', $this->storage->getSessiontoken());
		$this->assertEquals(5, $this->storage->getSessionExpires());
		$this->storage->purgeSession();
		$this->assertNull($this->storage->getSessiontoken());
		$this->assertEquals(-1, $this->storage->getSessionExpires());
	}

	public function testIsSessionEmptyReturnsTrue() {
		$this->storage->purgeSession();
		$this->assertTrue($this->storage->isSessionEmpty());
	}

	public function testIsSessionEmptyReturnsFalse() {
		$this->storage->setSessionToken('token');
		$this->assertFalse($this->storage->isSessionEmpty());
	}

	public function testPurgeUsernameThrowsEmptyUsernameException() {
		$this->storage->setUsername('username');
		$this->assertSame('username', $this->storage->getUsername());
		$this->storage->purgeUsername();
		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->storage->getUsername();
	}

	public function testIsUsernameEmptyReturnsTrue() {
		$this->storage->setUsername('username');
		$this->assertSame('username', $this->storage->getUsername());
		$this->storage->purgeUsername();
		$this->assertTrue($this->storage->isUsernameEmpty());

	}

	public function testIsUsernameEmptyReturnsFalse() {
		$this->storage->setUsername('username');
		$this->assertSame('username', $this->storage->getUsername());
		$this->assertFalse($this->storage->isUsernameEmpty());
	}

}
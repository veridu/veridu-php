<?php

namespace VeriduTest;

use Veridu\API\Profile;

class ProfileTest extends \PHPUnit_Framework_TestCase {

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
		$this->profile = $this
			->getMockBuilder('Veridu\API\Profile')
			->setConstructorArgs([$this->config['key'], $this->config['secret'], $this->config['version'], &$this->client, &$this->signature, &$this->storage])
			->setMethods(['setKey', 'setSecret', 'setVersion', 'fetch'])
			->getMock();
	}

	public function testCreateFilterValidMap () {
		$method = new \ReflectionMethod('Veridu\API\Profile', 'createFilter');
		$method->setAccessible(true);

		$result = $method->invoke($this->profile, 0xFFFF);
		$this->assertSame('all', $result);

		$result = $method->invoke($this->profile, 0x0001);
		$this->assertSame(['state'], $result);

		$result = $method->invoke($this->profile, 0x0002);
		$this->assertSame(['user'], $result);

		$result = $method->invoke($this->profile, 0x0004);
		$this->assertSame(['details'], $result);

		$result = $method->invoke($this->profile, 0x0008);
		$this->assertSame(['document'], $result);

		$result = $method->invoke($this->profile, 0x0010);
		$this->assertSame(['badges'], $result);

		$result = $method->invoke($this->profile, 0x020);
		$this->assertSame(['certificate'], $result);

		$result = $method->invoke($this->profile, 0x0040);
		$this->assertSame(['flags'], $result);

		$result = $method->invoke($this->profile, 0x0080);
		$this->assertSame(['facts'], $result);

		$result = $method->invoke($this->profile, 0x0100);
		$this->assertSame(['provider'], $result);

		$result = $method->invoke($this->profile, 0x0200);
		$this->assertSame(['cpr'], $result);

		$result = $method->invoke($this->profile, 0x0800);
		$this->assertSame(['nemid'], $result);

		$result = $method->invoke($this->profile, 0x1000);
		$this->assertSame(['otp'], $result);

		$result = $method->invoke($this->profile, 0x2000);
		$this->assertSame(['personal'], $result);
	}

	public function testCreateFilterInvalidMap () {
		$method = new \ReflectionMethod('Veridu\API\Profile', 'createFilter');
		$method->setAccessible(true);
		$result = $method->invoke($this->profile, 'test');
		$this->assertEmpty($result);
	}

	public function testCreateFilterEmptyMap () {
		$method = new \ReflectionMethod('Veridu\API\Profile', 'createFilter');
		$method->setAccessible(true);
		$result = $method->invoke($this->profile, []);
		$this->assertEmpty($result);
	}

	public function testRetrieveValidArgs () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->profile
			->method('fetch')
			->will($this->returnValue('test'));

		$this->assertSame('test', $this->profile->retrieve('filter', 'username'));
	}

	public function testRetrieveEmptySession () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->profile->retrieve('filter', 'username');
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
		$this->profile->retrieve('filter');
	}

	public function testRetrieveInvalidUsername () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));

		$this->setExpectedException('Veridu\API\Exception\InvalidUsername');
		$this->profile->retrieve('filter', '@123#');
	}
}

<?php

namespace VeriduTest;

use Veridu\API\Provider;

class ProviderTest extends \PHPUnit_Framework_TestCase {
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
		$this->provider = $this
			->getMockBuilder('Veridu\API\Provider')
			->setConstructorArgs([$this->config['key'], $this->config['secret'], $this->config['version'], &$this->client, &$this->signature, &$this->storage])
			->setMethods(['setKey', 'setSecret', 'setVersion', 'fetch'])
			->getMock();
	}

	public function testCreateOAuth1ValidArgs () {
		$this
			->storage
			->method('getUsername')
			->will($this->returnValue('username'));

		$this
			->provider
			->method('fetch')
			->will($this->returnValue([
				'status' => true,
				'task_id' => 'taskID',
				]));

		$this->assertSame('taskID', $this->provider->createOAuth1('provider', 'token', 'secret', 'appId'));
	}

	public function testCreateOAuth1EmptySession () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->provider->createOAuth1('provider', 'token', 'secret', 'appId');
	}

	public function testCreateOAuth1EmptyUsernameIsUsernameEmpty () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->provider->createOAuth1('provider', 'token', 'secret', 'appId');
	}

	public function testCreateOAuth2ValidArgs () {
		$this
			->storage
			->method('getUsername')
			->will($this->returnValue('username'));
		$this
			->provider
			->method('fetch')
			->will($this->returnValue([
				'status' => true,
				'task_id' => 'taskID',
				]));

		$this->assertSame('taskID', $this->provider->createOAuth2('provider', 'token', 'appId'));
	}

	public function testCreateOAuth2EmptySession () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->provider->createOAuth2('provider', 'token', 'appId');
	}

	public function testCreateOAuth2EmptyUsernameIsUsernameEmpty () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->provider->createOAuth2('provider', 'token', 'appId');
	}

	public function testGetAllDetailsValidUsername () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->provider
			->method('fetch')
			->will($this->returnValue('test'));

		$this->assertSame('test', $this->provider->getAllDetails('username'));
	}

	public function testGetAllDetailsEmptySession () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->provider->getAllDetails('username');
	}

	public function testGetAllDetailsEmptyUsernameIsUsernameEmptyFalse () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));

		$this
			->storage
			->method('getUsername')
			->will($this->returnValue('username'));

		$this
			->provider
			->method('fetch')
			->will($this->returnValue('test'));

		$this->assertSame('test', $this->provider->getAllDetails());
	}

	public function testGetAllDetailsEmptyUsernameIsUsernameEmpty () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->provider->getAllDetails();
	}

	public function testGetAllDetailsInvalidUsername () {
		$this->setExpectedException('Veridu\API\Exception\InvalidUsername');
		$this->provider->getAllDetails('@123#');
	}

	public function testCheckValidArgs () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->provider
			->method('fetch')
			->will($this->returnValue(['state' => true]));

		$this->assertTrue($this->provider->check('provider', 'username'));
	}

	public function testCheckEmptySession () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->provider->check('provider', 'username');
	}

	public function testCheckEmptyUsernameIsUsernameEmptyFalse () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));

		$this
			->storage
			->method('getUsername')
			->will($this->returnValue('username'));

		$this
			->provider
			->method('fetch')
			->will($this->returnValue(['state' => true]));

		$this->assertTrue($this->provider->check('provider'));
	}

	public function testCheckEmptyUsernameIsUsernameEmpty () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->provider->check('provider');
	}

	public function testCheckInvalidUsername () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));

		$this->setExpectedException('Veridu\API\Exception\InvalidUsername');
		$this->provider->check('provider', '@123#');
	}

	public function testListAllValidUsername () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->provider
			->method('fetch')
			->will($this->returnValue(['list'=>'username']));
		$this->assertSame('username', $this->provider->listAll('username'));
	}

	public function testListAllInvalidUsername () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this->setExpectedException('Veridu\API\Exception\InvalidUsername');
		$this->provider->listAll('@123#$');
	}

	public function testListAllEmptyUsernameIsUsernameEmptyFalse () {
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
			->provider
			->method('fetch')
			->will($this->returnValue(['list' => 'username']));

		$this->assertSame('username', $this->provider->listAll());
	}

	public function testListAllEmptySession () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->provider->listAll();
	}

	public function testListAllEmptyUsernameIsUsernameEmpty () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->provider->listAll();
	}
}

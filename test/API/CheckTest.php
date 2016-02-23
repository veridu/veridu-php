<?php

namespace VeriduTest;

use Veridu\API\Check;
use Veridu\Storage\MemoryStorage;
use Veridu\Exception;

class CheckTest extends \PHPUnit_Framework_TestCase {

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
		$this->check = $this
			->getMockBuilder('Veridu\API\Check')
			->setConstructorArgs([$this->config['key'], $this->config['secret'], $this->config['version'], &$this->client, &$this->signature, &$this->storage])
			->setMethods(['setKey', 'setSecret', 'setVersion', 'signedFetch'])
			->getMock();
	}

	public function testTracesmartSetupEmptyMap () {
		$method = new \ReflectionMethod('Veridu\API\Check', 'tracesmartSetup');
		$method->setAccessible(true);
		$result = $method->invoke($this->check, '');
		$this->assertEmpty($result);
	}

	public function testTracesmartSetupWithValidMap () {
		$method = new \ReflectionMethod('Veridu\API\Check', 'tracesmartSetup');
		$method->setAccessible(true);
		$result = $method->invoke($this->check, Check::NONE);
		$this->assertEquals([], $result);

		$method = new \ReflectionMethod('Veridu\API\Check', 'tracesmartSetup');
		$method->setAccessible(true);
		$result = $method->invoke($this->check, Check::TRACESMART_ALL);
		$this->assertEquals(['address', 'dob', 'driving', 'passport', 'credit-active'], $result);

		$method = new \ReflectionMethod('Veridu\API\Check', 'tracesmartSetup');
		$method->setAccessible(true);
		$result = $method->invoke($this->check, Check::TRACESMART_ADDRESS);
		$this->assertEquals(['address'], $result);

		$method = new \ReflectionMethod('Veridu\API\Check', 'tracesmartSetup');
		$method->setAccessible(true);
		$result = $method->invoke($this->check, Check::TRACESMART_DOB);
		$this->assertEquals(['dob'], $result);

		$method = new \ReflectionMethod('Veridu\API\Check', 'tracesmartSetup');
		$method->setAccessible(true);
		$result = $method->invoke($this->check, Check::TRACESMART_DRIVERLICENSE);
		$this->assertEquals(['driving'], $result);

		$method = new \ReflectionMethod('Veridu\API\Check', 'tracesmartSetup');
		$method->setAccessible(true);
		$result = $method->invoke($this->check, Check::TRACESMART_PASSPORT);
		$this->assertEquals(['passport'], $result);

		$method = new \ReflectionMethod('Veridu\API\Check', 'tracesmartSetup');
		$method->setAccessible(true);
		$result = $method->invoke($this->check, Check::TRACESMART_CREDITACTIVE);
		$this->assertEquals(['credit-active'], $result);

		$method = new \ReflectionMethod('Veridu\API\Check', 'tracesmartSetup');
		$method->setAccessible(true);
		$result = $method->invoke($this->check, Check::TRACESMART_DOB | Check::TRACESMART_DRIVERLICENSE);
		$this->assertEquals(['dob', 'driving'], $result);

		$method = new \ReflectionMethod('Veridu\API\Check', 'tracesmartSetup');
		$method->setAccessible(true);
		$result = $method->invoke($this->check, Check::TRACESMART_PASSPORT | Check::TRACESMART_ADDRESS | Check::TRACESMART_CREDITACTIVE);
		$this->assertEquals(['address',  'passport', 'credit-active'], $result);

	}

	public function testTracesmartSetupWithInvalidMap () {
		$method = new \ReflectionMethod('Veridu\API\Check', 'tracesmartSetup');
		$method->setAccessible(true);
		$result = $method->invoke($this->check, 'stub');
		$this->assertEquals([], $result);

	}

	public function testCreateValidArgs () {
		$this
			->check
			->method('signedFetch')
			->will($this->returnValue(['task_id' => 'task_id']));

		$this->assertSame('task_id', $this->check->create('tracesmart', Check::TRACESMART_ADDRESS | Check::TRACESMART_DOB, ['addParam1', 'addParam2'], 'test'));
	}

	public function testCreateEmptyUsernameAndIsUsernameEmptyFalse () {
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('getUsername')
			->will($this->returnValue('username'));

		$this->check = $this
			->getMockBuilder('Veridu\API\Check')
			->setConstructorArgs([$this->config['key'], $this->config['secret'], $this->config['version'], &$this->client, &$this->signature, &$this->storage])
			->setMethods(['setKey', 'setSecret', 'setVersion', 'signedFetch'])
			->getMock();
		$this
			->check
			->method('signedFetch')
			->will($this->returnValue(['task_id' => 'task_id']));

		$this->assertSame('task_id', $this->check->create('tracesmart', Check::TRACESMART_DRIVERLICENSE | Check::TRACESMART_DOB, ['addParam1', 'addParam2']));
	}

	public function testCreateEmptyUsernameAndIsUsernameEmpty () {
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));
		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->check->create('tracesmart', Check::TRACESMART_DRIVERLICENSE | Check::TRACESMART_DOB, ['addParam1', 'addParam2']);
	}

	public function testCreateInvalidUsername() {
	 	$this->setExpectedException('Veridu\API\Exception\InvalidUsername');
		$this->check->create('tracesmart', ['setup1', 'setup2'], ['addParam1', 'addParam2'], '@123!$%');
	}

	public function testCreateInvalidProvider () {
		$this->setExpectedException('Veridu\API\Exception\InvalidProvider');
		$this->check->create('invalidProvider', ['setup1', 'setup2'], ['addParam1', 'addParam2'], 'username');
	}

	public function testListAllValidUsername () {
		$this
			->check
			->method('signedFetch')
			->will($this->returnValue(['data' => 'test']));

		$this->assertSame('test', $this->check->listAll('username'));
	}

	public function testListAllEmptyUsernameIsUsernameEmptyFalse () {
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('getUsername')
			->will($this->returnValue('username'));
		$this
			->check
			->method('signedFetch')
			->will($this->returnValue(['data' => 'test']));

		$this->assertSame('test', $this->check->listAll());
	}

	public function testListAllEmptyUsernameIsUsernameEmpty () {
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->check->listAll();
	}

	public function testListAllInvalidUsername () {
		$this->setExpectedException('Veridu\API\Exception\InvalidUsername');
		$this->check->listAll('@123#');
	}

	public function testDetailsValidArgs () {
		$this
			->check
			->method('signedFetch')
			->will($this->returnValue(['data' => 'test']));

		$this->assertSame('test', $this->check->details('username', 'provider', Check::TRACESMART_DOB, Check::FILTER_INFO));
	}

	public function testDetailsSuccessfulInvoke() {
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('getUsername')
			->will($this->returnValue('username'));
		$this
			->check
			->method('signedFetch')
			->will($this->returnValue(['data' => 'test']));

		$this->assertSame('test', $this->check->details('provider', Check::TRACESMART_DOB, Check::FILTER_INFO));
	}

	public function testDetailsEmptyUsernameIsUsernameEmpty () {
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->check->details('', 'provider', Check::TRACESMART_DOB, Check::FILTER_INFO);
	}

	public function testDetailsInvalidUsername () {
		$this->setExpectedException('Veridu\API\Exception\InvalidUsername');
		$this->check->details('@123#', 'provider', Check::TRACESMART_DOB, Check::FILTER_INFO);
	}

}

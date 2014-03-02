<?php

namespace VeriduTest\Unit\SDK;

use Veridu\Common\Config;
use Veridu\SDK\API;
use Veridu\SDK\Session;

class SessionTest extends \PHPUnit_Framework_TestCase {

	protected $session = null;

	protected function setUp() {
		$api = $this->getMockBuilder('Veridu\\SDK\\API')
			->disableOriginalConstructor()
			->getMock();
		$this->session = new Session($api);
	}

	public function testSetAndGetAPI() {
		$config = new Config(
			'client',
			'secret',
			'version'
		);
		$http = $this->getMockForAbstractClass('Veridu\\HTTPClient\\AbstractClient');
		$signature = $this->getMockForAbstractClass('Veridu\\Signature\\AbstractSignature');
		$api = new API($config, $http, $signature);
		$this->session->setAPI($api);
		$this->assertSame($api, $this->session->getAPI());
	}

	public function testSetAndGetToken() {
		$config = new Config(
			'client',
			'secret',
			'version'
		);
		$http = $this->getMockForAbstractClass('Veridu\\HTTPClient\\AbstractClient');
		$signature = $this->getMockForAbstractClass('Veridu\\Signature\\AbstractSignature');
		$api = new API($config, $http, $signature);
		$this->session->setAPI($api);
		$this->assertNull($this->session->getToken());
		$this->session->setToken('session');
		$this->assertSame('session', $this->session->getToken());
	}

	public function testSetAndGetExpires() {
		$this->assertSame(-1, $this->session->getExpires());
		$this->session->setExpires(100);
		$this->assertSame(100, $this->session->getExpires());
	}

	public function testSetAndGetUsername() {
		$this->assertNull($this->session->getUsername());
		$this->session->setUsername('username');
		$this->assertSame('username', $this->session->getUsername());
	}

	public function testCreateInvalidResponse() {
		$api = $this->getMockBuilder('Veridu\\SDK\\API')
			->disableOriginalConstructor()
			->getMock();
		$api->expects($this->once())
			->method('signedFetch')
			->will($this->throwException(new \Veridu\SDK\Exception\InvalidResponse));
		$this->session->setAPI($api);
		$this->setExpectedException('Veridu\\SDK\\Exception\\InvalidResponse');
		$this->session->create();
	}

	public function testCreateInvalidFormat() {
		$api = $this->getMockBuilder('Veridu\\SDK\\API')
			->disableOriginalConstructor()
			->getMock();
		$api->expects($this->once())
			->method('signedFetch')
			->will($this->throwException(new \Veridu\SDK\Exception\InvalidFormat));
		$this->session->setAPI($api);
		$this->setExpectedException('Veridu\\SDK\\Exception\\InvalidFormat');
		$this->session->create();
	}

	public function testCreateAPIError() {
		$api = $this->getMockBuilder('Veridu\\SDK\\API')
			->disableOriginalConstructor()
			->getMock();
		$api->expects($this->once())
			->method('signedFetch')
			->will($this->throwException(new \Veridu\SDK\Exception\APIError));
		$this->session->setAPI($api);
		$this->setExpectedException('Veridu\\SDK\\Exception\\APIError');
		$this->session->create();
	}

	public function testCreateNonceMismatch() {
		$api = $this->getMockBuilder('Veridu\\SDK\\API')
			->disableOriginalConstructor()
			->getMock();
		$api->expects($this->once())
			->method('signedFetch')
			->will($this->throwException(new \Veridu\Signature\Exception\NonceMismatch));
		$this->session->setAPI($api);
		$this->setExpectedException('Veridu\\Signature\\Exception\\NonceMismatch');
		$this->session->create();
	}

	public function testCreateReadOnly() {
		$payload = array(
			'status' => true,
			'token' => 'session',
			'expires' => 100
		);
		$api = $this->getMockBuilder('Veridu\\SDK\\API')
			->disableOriginalConstructor()
			->getMock();
		$api->expects($this->once())
			->method('signedFetch')
			->will($this->returnValue($payload));
		$this->session->setAPI($api);
		$this->session->create(true);
		$this->assertSame(100, $this->session->getExpires());
	}

	public function testCreateReadWrite() {
		$payload = array(
			'status' => true,
			'token' => 'session',
			'expires' => 100
		);
		$api = $this->getMockBuilder('Veridu\\SDK\\API')
			->disableOriginalConstructor()
			->getMock();
		$api->expects($this->once())
			->method('signedFetch')
			->will($this->returnValue($payload));
		$this->session->setAPI($api);
		$this->session->create(false);
		$this->assertSame(100, $this->session->getExpires());
	}

	public function testExtendEmptySession() {
		$this->setExpectedException('Veridu\\SDK\\Exception\\EmptySession');
		$this->session->extend();
	}

	public function testExtendInvalidResponse() {
		$api = $this->getMockBuilder('Veridu\\SDK\\API')
			->disableOriginalConstructor()
			->getMock();
		$api->expects($this->once())
			->method('getSession')
			->will($this->returnValue('session'));
		$api->expects($this->once())
			->method('signedFetch')
			->will($this->throwException(new \Veridu\SDK\Exception\InvalidResponse));
		$this->session->setAPI($api);
		$this->setExpectedException('Veridu\\SDK\\Exception\\InvalidResponse');
		$this->session->extend();
	}

	public function testExtendInvalidFormat() {
		$api = $this->getMockBuilder('Veridu\\SDK\\API')
			->disableOriginalConstructor()
			->getMock();
		$api->expects($this->once())
			->method('getSession')
			->will($this->returnValue('session'));
		$api->expects($this->once())
			->method('signedFetch')
			->will($this->throwException(new \Veridu\SDK\Exception\InvalidFormat));
		$this->session->setAPI($api);
		$this->setExpectedException('Veridu\\SDK\\Exception\\InvalidFormat');
		$this->session->extend();
	}

	public function testExtendAPIError() {
		$api = $this->getMockBuilder('Veridu\\SDK\\API')
			->disableOriginalConstructor()
			->getMock();
		$api->expects($this->once())
			->method('getSession')
			->will($this->returnValue('session'));
		$api->expects($this->once())
			->method('signedFetch')
			->will($this->throwException(new \Veridu\SDK\Exception\APIError));
		$this->session->setAPI($api);
		$this->setExpectedException('Veridu\\SDK\\Exception\\APIError');
		$this->session->extend();
	}

	public function testExtendNonceMismatch() {
		$api = $this->getMockBuilder('Veridu\\SDK\\API')
			->disableOriginalConstructor()
			->getMock();
		$api->expects($this->once())
			->method('getSession')
			->will($this->returnValue('session'));
		$api->expects($this->once())
			->method('signedFetch')
			->will($this->throwException(new \Veridu\Signature\Exception\NonceMismatch));
		$this->session->setAPI($api);
		$this->setExpectedException('Veridu\\Signature\\Exception\\NonceMismatch');
		$this->session->extend();
	}

	public function testExtend() {
		$payload = array(
			'status' => true,
			'expires' => 100
		);
		$api = $this->getMockBuilder('Veridu\\SDK\\API')
			->disableOriginalConstructor()
			->getMock();
		$api->expects($this->once())
			->method('getSession')
			->will($this->returnValue('session'));
		$api->expects($this->once())
			->method('signedFetch')
			->will($this->returnValue($payload));
		$this->assertSame(-1, $this->session->getExpires());
		$this->session->setAPI($api);
		$this->session->extend();
		$this->assertSame(100, $this->session->getExpires());
	}

	public function testExpireEmptySession() {
		$this->setExpectedException('Veridu\\SDK\\Exception\\EmptySession');
		$this->session->expire();
	}

	public function testExpireInvalidResponse() {
		$api = $this->getMockBuilder('Veridu\\SDK\\API')
			->disableOriginalConstructor()
			->getMock();
		$api->expects($this->once())
			->method('getSession')
			->will($this->returnValue('session'));
		$api->expects($this->once())
			->method('signedFetch')
			->will($this->throwException(new \Veridu\SDK\Exception\InvalidResponse));
		$this->session->setAPI($api);
		$this->setExpectedException('Veridu\\SDK\\Exception\\InvalidResponse');
		$this->session->expire();
	}

	public function testExpireInvalidFormat() {
		$api = $this->getMockBuilder('Veridu\\SDK\\API')
			->disableOriginalConstructor()
			->getMock();
		$api->expects($this->once())
			->method('getSession')
			->will($this->returnValue('session'));
		$api->expects($this->once())
			->method('signedFetch')
			->will($this->throwException(new \Veridu\SDK\Exception\InvalidFormat));
		$this->session->setAPI($api);
		$this->setExpectedException('Veridu\\SDK\\Exception\\InvalidFormat');
		$this->session->expire();
	}

	public function testExpireAPIError() {
		$api = $this->getMockBuilder('Veridu\\SDK\\API')
			->disableOriginalConstructor()
			->getMock();
		$api->expects($this->once())
			->method('getSession')
			->will($this->returnValue('session'));
		$api->expects($this->once())
			->method('signedFetch')
			->will($this->throwException(new \Veridu\SDK\Exception\APIError));
		$this->session->setAPI($api);
		$this->setExpectedException('Veridu\\SDK\\Exception\\APIError');
		$this->session->expire();
	}

	public function testExpireNonceMismatch() {
		$api = $this->getMockBuilder('Veridu\\SDK\\API')
			->disableOriginalConstructor()
			->getMock();
		$api->expects($this->once())
			->method('getSession')
			->will($this->returnValue('session'));
		$api->expects($this->once())
			->method('signedFetch')
			->will($this->throwException(new \Veridu\Signature\Exception\NonceMismatch));
		$this->session->setAPI($api);
		$this->setExpectedException('Veridu\\Signature\\Exception\\NonceMismatch');
		$this->session->expire();
	}

	public function testExpire() {
		$payload = array(
			'status' => true,
			'expires' => 100
		);
		$api = $this->getMockBuilder('Veridu\\SDK\\API')
			->disableOriginalConstructor()
			->getMock();
		$api->expects($this->once())
			->method('getSession')
			->will($this->returnValue('session'));
		$api->expects($this->once())
			->method('signedFetch')
			->will($this->returnValue($payload));
		$this->session->setAPI($api);
		$this->session->setExpires(100);
		$this->session->setUsername('username');
		$this->session->expire();
		$this->assertSame(-1, $this->session->getExpires());
		$this->assertNull($this->session->getUsername());
	}

	public function testAssignEmptySession() {
		$this->setExpectedException('Veridu\\SDK\\Exception\\EmptySession');
		$this->session->assign('');
	}

	public function testAssignInvalidUsername() {
		$api = $this->getMockBuilder('Veridu\\SDK\\API')
			->disableOriginalConstructor()
			->getMock();
		$api->expects($this->once())
			->method('getSession')
			->will($this->returnValue('session'));
		$this->session->setAPI($api);
		$this->setExpectedException('Veridu\\SDK\\Exception\\InvalidUsername');
		$this->session->assign('');
	}

	public function testAssignInvalidResponse() {
		$api = $this->getMockBuilder('Veridu\\SDK\\API')
			->disableOriginalConstructor()
			->getMock();
		$api->expects($this->once())
			->method('getSession')
			->will($this->returnValue('session'));
		$api->expects($this->once())
			->method('signedFetch')
			->will($this->throwException(new \Veridu\SDK\Exception\InvalidResponse));
		$this->session->setAPI($api);
		$this->setExpectedException('Veridu\\SDK\\Exception\\InvalidResponse');
		$this->session->assign('username');
	}

	public function testAssignInvalidFormat() {
		$api = $this->getMockBuilder('Veridu\\SDK\\API')
			->disableOriginalConstructor()
			->getMock();
		$api->expects($this->once())
			->method('getSession')
			->will($this->returnValue('session'));
		$api->expects($this->once())
			->method('signedFetch')
			->will($this->throwException(new \Veridu\SDK\Exception\InvalidFormat));
		$this->session->setAPI($api);
		$this->setExpectedException('Veridu\\SDK\\Exception\\InvalidFormat');
		$this->session->assign('username');
	}

	public function testAssignAPIError() {
		$api = $this->getMockBuilder('Veridu\\SDK\\API')
			->disableOriginalConstructor()
			->getMock();
		$api->expects($this->once())
			->method('getSession')
			->will($this->returnValue('session'));
		$api->expects($this->once())
			->method('signedFetch')
			->will($this->throwException(new \Veridu\SDK\Exception\APIError));
		$this->session->setAPI($api);
		$this->setExpectedException('Veridu\\SDK\\Exception\\APIError');
		$this->session->assign('username');
	}

	public function testAssignNonceMismatch() {
		$api = $this->getMockBuilder('Veridu\\SDK\\API')
			->disableOriginalConstructor()
			->getMock();
		$api->expects($this->once())
			->method('getSession')
			->will($this->returnValue('session'));
		$api->expects($this->once())
			->method('signedFetch')
			->will($this->throwException(new \Veridu\Signature\Exception\NonceMismatch));
		$this->session->setAPI($api);
		$this->setExpectedException('Veridu\\Signature\\Exception\\NonceMismatch');
		$this->session->assign('username');
	}

	public function testAssign() {
		$payload = array(
			'status' => true
		);
		$api = $this->getMockBuilder('Veridu\\SDK\\API')
			->disableOriginalConstructor()
			->getMock();
		$api->expects($this->once())
			->method('getSession')
			->will($this->returnValue('session'));
		$api->expects($this->once())
			->method('signedFetch')
			->will($this->returnValue($payload));
		$this->session->setAPI($api);
		$this->assertNull($this->session->getUsername());
		$this->session->assign('username');
		$this->assertSame('username', $this->session->getUsername());
	}

}
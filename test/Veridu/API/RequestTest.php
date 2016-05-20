<?php

namespace VeriduTest;

use Veridu\API\Request;
use Veridu\Storage\MemoryStorage;
use Veridu\Exception;
use Veridu\HTTPClient;

class RequestTest extends \PHPUnit_Framework_TestCase {

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
		$this->request = $this
			->getMockBuilder('Veridu\API\Request')
			->setConstructorArgs([$this->config['key'], $this->config['secret'], $this->config['version'], &$this->client, &$this->signature, &$this->storage])
			->setMethods(['setKey', 'setSecret', 'setVersion', 'signedFetch', 'fetch'])
			->getMock();
	}

	public function testCreateReturnsSelf () {
		$this
			->storage
			->method('getUsername')
			->will($this->returnValue('username'));
		$this
			->request
			->method('fetch')
			->will($this->returnValue($this->request));
		$this->assertSame($this->request, $this->request->create('usernameTo', 'type', 'message'));
	}

	public function testCreateThrowsEmptySession () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));
		$this->setExpectedException('Veridu\API\Exception\EmptySession');
	 	$this->request->create('usernameTo', 'type', 'message');
	}

	public function testCreateThrowsEmptyUsername () {
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));
		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->request->create('usernameTo', 'type', 'message');
	}

	public function testStatsReturnsArray () {
		$this
			->storage
			->method('getUsername')
			->will($this->returnValue('username'));
		$this
			->request
			->method('fetch')
			->will($this->returnValue(
				[
					'test' => 'test'
				]
			));
		$this->assertSame(['test' => 'test'], $this->request->stats());
	}

	public function testStatsThrowsEmptySession () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));
		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->request->stats();
	}

	public function testStatsThrowsEmptyUsername () {
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));
		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->request->stats();
	}

	public function testRetrieveReturnsArray () {
		$this
			->storage
			->method('getUsername')
			->will($this->returnValue('username'));
		$this
			->request
			->method('fetch')
			->will($this->returnValue(
				[
					'list' => [
						'test' => 'test'
					]
				]
			));
		$this->assertSame(['test' => 'test'], $this->request->retrieve('filter-all', 100, 10));
	}

	public function testRetrieveThrowsEmptySession () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));
		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->request->retrieve('filter-all', 100, 10);
	}

	public function testRetrieveThrowsEmptyUsername () {
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));
		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->request->retrieve('filter-all', 100, 10);
	}
}
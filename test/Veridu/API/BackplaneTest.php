<?php

namespace VeriduTest;

use Veridu\API\Backplane;
use Veridu\Storage\MemoryStorage;
use Veridu\Exception;
use Veridu\HTTPClient;

class BackplaneTest extends \PHPUnit_Framework_TestCase {

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
		$this->backplane = $this
			->getMockBuilder('Veridu\API\Backplane')
			->setConstructorArgs([$this->config['key'], $this->config['secret'], $this->config['version'], &$this->client, &$this->signature, &$this->storage])
			->setMethods(['setKey', 'setSecret', 'setVersion', 'signedFetch', 'fetch'])
			->getMock();
	}

	public function testSetupReturnsSelf() {
		$this
			->storage
			->method('getUsername')
			->will($this->returnValue('username'));
		$this
			->backplane
			->method('signedFetch')
			->will($this->returnValue($this->backplane));
		$this->assertSame($this->backplane, $this->backplane->setup('channel'));
	}

	public function testSetupThrowsEmptySession() {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->backplane->setup('channel');
	}

	public function testSetupThowsEmptyUsername() {
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));
		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->backplane->setup('channel');
	}
}
<?php

namespace VeriduTest;

use Veridu\API\Batch;
use Veridu\Storage\MemoryStorage;
use Veridu\Exception;
use Veridu\HTTPClient;

class BatchTest extends \PHPUnit_Framework_TestCase {

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
		$this->batch = $this
			->getMockBuilder('Veridu\API\Batch')
			->setConstructorArgs([$this->config['key'], $this->config['secret'], $this->config['version'], &$this->client, &$this->signature, &$this->storage])
			->setMethods(['setKey', 'setSecret', 'setVersion', 'signedFetch', 'fetch'])
			->getMock();
	}

	public function testSendReturnsArray() {
		$this
			->storage
			->method('getUsername')
			->will($this->returnValue('username'));
		$this
			->batch
			->method('fetch')
			->will($this->returnValue(
				[
					'batch' =>
					[
						'test' => 'test'
					]
				]
			));

		$this->assertSame(['test' => 'test'], $this->batch->send(['resource' => 'resource.method', 'url' => 'url.com']));
	}

	public function testSendThrowsEmptySession() {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));
		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->batch->send(['resource' => 'resource.method', 'url' => 'url.com']);
	}

	public function testSendThrowsEmptyUsername() {
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));
		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->batch->send(['resource' => 'resource.method', 'url' => 'url.com']);
	}

}

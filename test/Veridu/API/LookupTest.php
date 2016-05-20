<?php

namespace VeriduTest;

use Veridu\API\Lookup;
use Veridu\Storage\MemoryStorage;
use Veridu\Exception;
use Veridu\HTTPClient;

class LookupTest extends \PHPUnit_Framework_TestCase {

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
		$this->lookup = $this
			->getMockBuilder('Veridu\API\Lookup')
			->setConstructorArgs([$this->config['key'], $this->config['secret'], $this->config['version'], &$this->client, &$this->signature, &$this->storage])
			->setMethods(['setKey', 'setSecret', 'setVersion', 'signedFetch', 'fetch'])
			->getMock();
	}

	public function testSearchReturnsArray () {
		$this
			->storage
			->method('getUsername')
			->will($this->returnValue('username'));
		$this
			->lookup
			->method('fetch')
			->will($this->returnValue(
				[
					'results' => [
						'test' => 'test'
					]
				]
			));
		$this->assertSame(['test' => 'test'], $this->lookup->search('region', 'postcode'));
	}

	public function testSearchThrowsEmptySession () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));
		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->lookup->search('region', 'postcode');
	}

	public function testSearchThrowsEmptyUsername () {
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));
		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->lookup->search('region', 'postcode');
	}

	public function testDetailsReturnsArray () {
		$this
			->storage
			->method('getUsername')
			->will($this->returnValue('username'));
		$this
			->lookup
			->method('fetch')
			->will($this->returnValue(
				[
					'info' => [
						'test' => 'test'
					]
				]
			));
		$this->assertSame(['test' => 'test'], $this->lookup->details('region', 'lookupId'));
	}

	public function testDetailsThrowsEmptySession () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));
		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->lookup->details('region', 'lookupId');
	}

	public function testDetailsThrowsEmptyUsername () {
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));
		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->lookup->details('region', 'lookupId');
	}
}

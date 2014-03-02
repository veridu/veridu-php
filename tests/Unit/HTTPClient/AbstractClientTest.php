<?php

namespace VeriduTest\Unit\HTTPClient;

class AbstractClientTest extends \PHPUnit_Framework_TestCase {

	private $client = null;

	protected function setUp() {
		$this->client = $this->getMockForAbstractClass('Veridu\\HTTPClient\\AbstractClient');
	}

	public function testConstructCorrectInterface() {
		$this->assertInstanceOf('Veridu\\HTTPClient\\HTTPClient', $this->client);
	}

	public function testSetAndGetHeader() {
		$this->assertNull($this->client->getHeader('header'));
		$this->client->setHeader('header', 'value');
		$this->assertSame('value', $this->client->getHeader('header'));
	}

	public function testUnsetHeader() {
		$this->assertNull($this->client->getHeader('header'));
		$this->client->setHeader('header', 'value');
		$this->assertSame('value', $this->client->getHeader('header'));
		$this->client->unsetHeader('header');
		$this->assertNull($this->client->getHeader('header'));
	}

}
<?php

namespace VeriduTest\Unit\Signature;

use Veridu\Signature\HMAC;

class HMACTest extends \PHPUnit_Framework_TestCase {

	protected $hmac = null;

	protected function setUp() {
		$this->hmac = new HMAC;
	}

	public function testConstructCorrectInterface() {
		$this->assertInstanceOf('Veridu\\Signature\\Signature', $this->hmac);
	}

	public function testDefaultHash() {
		$this->assertSame('sha1', $this->hmac->getHash());
	}

	public function testSetAndGetHash() {
		$this->hmac->setHash('sha256');
		$this->assertSame('sha256', $this->hmac->getHash());
	}

	public function testSHA1Sign() {
		$sign = $this->hmac->sign('client', 'secret', 'version', 'method', 'resource');
		$query = sprintf("client=client&hash=sha1&method=METHOD&nonce=%s&resource=resource&timestamp=%d&version=version", $this->hmac->lastNonce(), time());
		$signature = hash_hmac('sha1', $query, 'secret');
		$this->assertSame("{$query}&signature={$signature}", $sign);

	}

	public function testSHA256Sign() {
		$this->hmac->setHash('sha256');
		$sign = $this->hmac->sign('client', 'secret', 'version', 'method', 'resource');
		$query = sprintf("client=client&hash=sha256&method=METHOD&nonce=%s&resource=resource&timestamp=%d&version=version", $this->hmac->lastNonce(), time());
		$signature = hash_hmac('sha256', $query, 'secret');
		$this->assertSame("{$query}&signature={$signature}", $sign);
	}

}
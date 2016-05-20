<?php

namespace VeriduTest\Signature;

class AbstractSignatureTest extends \PHPUnit_Framework_TestCase {

	private $signature = null;

	protected function setUp() {
		$this->signature = $this->getMockForAbstractClass('Veridu\Signature\HMAC');
	}

	public function testConstructCorrectInterface() {
		$this->assertInstanceOf('Veridu\\Signature\\AbstractSignature', $this->signature);
	}

	public function testLastNonce() {
		$this->assertNull($this->signature->lastNonce());
	}
}
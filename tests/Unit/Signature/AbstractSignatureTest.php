<?php

namespace VeriduTest\Unit\Signature;

class AbstractSignatureTest extends \PHPUnit_Framework_TestCase {

	private $signature = null;

	protected function setUp() {
		$this->signature = $this->getMockForAbstractClass('Veridu\\Signature\\AbstractSignature');
	}

	public function testConstructCorrectInterface() {
		$this->assertInstanceOf('Veridu\\Signature\\Signature', $this->signature);
	}

	public function testLastNonce() {
		$this->assertNull($this->signature->lastNonce());
	}
}
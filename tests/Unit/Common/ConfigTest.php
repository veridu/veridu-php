<?php

namespace VeriduTest\Unit\Common;

use Veridu\Common\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase {

	protected $config = null;

	protected function setUp() {
		$this->config = new Config('client', 'secret', 'version');
	}

	public function testGetClient() {
		$this->assertSame('client', $this->config->getClient());
	}

	public function testGetSecret() {
		$this->assertSame('secret', $this->config->getSecret());
	}

	public function testGetVersion() {
		$this->assertSame('version', $this->config->getVersion());
	}


}
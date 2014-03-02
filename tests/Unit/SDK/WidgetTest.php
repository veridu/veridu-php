<?php

namespace VeriduTest\Unit\SDK;

use Veridu\Common\Config;
use Veridu\SDK\Widget;

class WidgetTest extends \PHPUnit_Framework_TestCase {

	protected $widget = null;

	protected function setUp() {
		$config = new Config(
			'client',
			'secret',
			'version'
		);
		$this->widget = new Widget($config, 'session', 'username');
	}

	public function testSetAndGetConfig() {
		$config = new Config(
			'testing-client',
			'testing-secret',
			'testing-version'
		);
		$this->widget->setConfig($config);
		$this->assertSame($config, $this->widget->getConfig());
	}

	public function testSetAndGetSession() {
		$this->assertSame('session', $this->widget->getSession());
		$this->widget->setSession('testing-session');
		$this->assertSame('testing-session', $this->widget->getSession());
	}

	public function testSetAndGetUsername() {
		$this->assertSame('username', $this->widget->getUsername());
		$this->widget->setUsername('testing-username');
		$this->assertSame('testing-username', $this->widget->getUsername());
	}

	public function testEmptyUsernameGetEndpoint() {
		$this->setExpectedException('Veridu\\SDK\\Exception\\EmptyWidgetUsername');
		$this->widget->setUsername('');
		$this->widget->getEndpoint('widget/endpoint');
	}

	public function testEmptySessionGetEndpoint() {
		$this->setExpectedException('Veridu\\SDK\\Exception\\EmptyWidgetSession');
		$this->widget->setSession('');
		$this->widget->getEndpoint('widget/endpoint');
	}

	public function testGetEndpointWithQueryArray() {
		$endpoint = $this->widget->getEndpoint('widget/endpoint', array('key' => 'value'));
		$this->assertSame('https://widget.veridu.com/version/widget/endpoint/client/username?key=value&session=session', $endpoint);
	}

	public function testGetEndpointWithQueryString() {
		$endpoint = $this->widget->getEndpoint('widget/endpoint', 'key=value');
		$this->assertSame('https://widget.veridu.com/version/widget/endpoint/client/username?key=value&session=session', $endpoint);
	}

	public function testGetEndpointWithoutQueryString() {
		$endpoint = $this->widget->getEndpoint('widget/endpoint');
		$this->assertSame('https://widget.veridu.com/version/widget/endpoint/client/username?session=session', $endpoint);
	}

	public function testGetEndpointWithLeadingSlashResource() {
		$endpoint = $this->widget->getEndpoint('/widget/endpoint');
		$this->assertSame('https://widget.veridu.com/version/widget/endpoint/client/username?session=session', $endpoint);
	}

	public function testGetEndpointWithTrailingSlashResource() {
		$endpoint = $this->widget->getEndpoint('widget/endpoint/');
		$this->assertSame('https://widget.veridu.com/version/widget/endpoint/client/username?session=session', $endpoint);
	}
}
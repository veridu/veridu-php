<?php

namespace Veridu;

class APITest extends \PHPUnit_Framework_TestCase {

	protected $api = null;
	public $key = 'key';
	public $secret = 'secret';
	public $version = '0.3';

	protected function setUp() {
		$this->api = API::factory($this->key, $this->secret, $this->version);
	}

	public function testClassNameResolutionReturnsRightNamespace() {
		$method = new \ReflectionMethod('\Veridu\API', 'classNameResolution');
		$method->setAccessible(true);

		$result = $method->invoke($this->api, 'application');
		$this->assertSame('\\Veridu\\API\\Application', $result);

		$result = $method->invoke($this->api, 'backplane');
		$this->assertSame('\\Veridu\\API\\Backplane', $result);

		$result = $method->invoke($this->api, 'badge');
		$this->assertSame('\\Veridu\\API\\Badge', $result);

		$result = $method->invoke($this->api, 'batch');
		$this->assertSame('\\Veridu\\API\\Batch', $result);

		$result = $method->invoke($this->api, 'certificate');
		$this->assertSame('\\Veridu\\API\\Certificate', $result);

		$result = $method->invoke($this->api, 'check');
		$this->assertSame('\\Veridu\\API\\Check', $result);

		$result = $method->invoke($this->api, 'cloned');
		$this->assertSame('\\Veridu\\API\\Cloned', $result);

		$result = $method->invoke($this->api, 'credential');
		$this->assertSame('\\Veridu\\API\\Credential', $result);

		$result = $method->invoke($this->api, 'details');
		$this->assertSame('\\Veridu\\API\\Details', $result);

		$result = $method->invoke($this->api, 'facts');
		$this->assertSame('\\Veridu\\API\\Facts', $result);

		$result = $method->invoke($this->api, 'hook');
		$this->assertSame('\\Veridu\\API\\Hook', $result);

		$result = $method->invoke($this->api, 'lookup');
		$this->assertSame('\\Veridu\\API\\Lookup', $result);

		$result = $method->invoke($this->api, 'otp');
		$this->assertSame('\\Veridu\\API\\OTP', $result);

		$result = $method->invoke($this->api, 'password');
		$this->assertSame('\\Veridu\\API\\Password', $result);

		$result = $method->invoke($this->api, 'personal');
		$this->assertSame('\\Veridu\\API\\Personal', $result);

		$result = $method->invoke($this->api, 'profile');
		$this->assertSame('\\Veridu\\API\\Profile', $result);

		$result = $method->invoke($this->api, 'provider');
		$this->assertSame('\\Veridu\\API\\Provider', $result);

		$result = $method->invoke($this->api, 'raw');
		$this->assertSame('\\Veridu\\API\\Raw', $result);

		$result = $method->invoke($this->api, 'request');
		$this->assertSame('\\Veridu\\API\\Request', $result);

		$result = $method->invoke($this->api, 'sso');
		$this->assertSame('\\Veridu\\API\\SSO', $result);

		$result = $method->invoke($this->api, 'session');
		$this->assertSame('\\Veridu\\API\\Session', $result);

		$result = $method->invoke($this->api, 'state');
		$this->assertSame('\\Veridu\\API\\State', $result);

		$result = $method->invoke($this->api, 'task');
		$this->assertSame('\\Veridu\\API\\Task', $result);

		$result = $method->invoke($this->api, 'user');
		$this->assertSame('\\Veridu\\API\\User', $result);

	}

	public function testFactoryReturnAPIObject() {
		$api = API::factory($this->key, $this->secret, $this->version);
		$this->assertInstanceOf('Veridu\API', $api);
		$this->assertInstanceOf('Veridu\Storage\StorageInterface', $api->getStorage());
		$this->assertInstanceOf('Veridu\Signature\SignatureInterface', $api->getSignature());
	}
}

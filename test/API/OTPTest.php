<?php

namespace Veridu\API;

use Veridu\API\OTP;

class OTPTest extends \PHPUnit_Framework_TestCase {

	protected function  setUp() {
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
		$this->otp = $this
			->getMockBuilder('Veridu\API\OTP')
			->setConstructorArgs([$this->config['key'], $this->config['secret'], $this->config['version'], &$this->client, &$this->signature, &$this->storage])
			->setMethods(['setKey', 'setSecret', 'setVersion', 'fetch'])
			->getMock();

	}

	public function testListAllWithValidUsernameParameter () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->otp
			->method('fetch')
			->will($this->returnValue(['list'=>'username']));
		$this->assertSame('username', $this->otp->listAll('username'));
	}

	public function testListAllInvalidUsername () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));

		$this->setExpectedException('Veridu\API\Exception\InvalidUsername');
		$this->otp->listAll('@123#$');
	}

	public function testListAllWithValidUsernameFromStorage () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('getUsername')
			->will($this->returnValue('username'));
		$this
			->otp
			->method('fetch')
			->will($this->returnValue(['list' => 'username']));

		$this->assertSame('username', $this->otp->listAll());
	}

	public function testListAllWithEmptySession () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->otp->listAll();
	}

	public function testListAllWithEmptyUsername () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->otp->listAll();
	}

	public function testCreateEmailWithArgs () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(false));
		$this
			->otp
			->method('fetch')
			->will($this->returnValue('test'));

		$this->assertSame('test', $this->otp->createEmail('email@email.com', 'extended', 'url'));
	}

	public function testCreateEmailSessionEmpty () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->otp->createEmail('email@email.com', 'extended', 'url');
	}

	public function testCreateEmailEmptyUsername () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));
		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->otp->createEmail('email@email.com', 'extended', 'url');
	}

	public function testCreateSMSValidPhone () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(false));
		$this
			->otp
			->method('fetch')
			->will($this->returnValue('test'));

		$this->assertSame('test', $this->otp->createSMS('999999999'));
	}

	public function testCreateSMSSessionEmpty () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));
			
		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->otp->createSMS('999999999');
	}

	public function testCreateSMSEmptyUsernameIsUsernameEmpty () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->otp->createSMS('999999999');
	}

	public function testResendEmailValidEmail () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(false));
		$this
			->otp
			->method('fetch')
			->will($this->returnValue('test'));

		$this->assertSame($this->otp, $this->otp->resendEmail('email@email.com'));
	}

	public function testResendEmailSessionEmpty () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->otp->resendEmail('email@email.com');
	}

	public function testResendEmailEmptyUsernameIsUsernameEmpty () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->otp->resendEmail('email@email.com');
	}

	public function testResendSMSValidPhone () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(false));
		$this
			->otp
			->method('fetch')
			->will($this->returnValue('test'));

		$this->assertSame($this->otp, $this->otp->resendSMS('999999999'));
	}

	public function testResendSMSEmptySession  () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->otp->resendSMS('999999999');
	}

	public function testResendSMSEmptyUsernameIsUsernameEmpty () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->otp->resendSMS('999999999');
	}

	public function testCheckEmailValidEmail () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(false));
		$this
			->otp
			->method('fetch')
			->will($this->returnValue(['state' => true]));

		$this->assertTrue($this->otp->checkEmail('email@email.com'));
	}

	public function testCheckEmailEmptySession () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->otp->checkEmail('email@email.com');
	}

	public function testCheckEmailEmptyUsernameIsUsernameEmpty () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->otp->checkEmail('email@email.com');
	}

	public function testCheckSMSValidPhone () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(false));
		$this
			->otp
			->method('fetch')
			->will($this->returnValue(['state' => true]));

		$this->assertTrue($this->otp->checkSMS('999999999'));
	}

	public function testCheckSMSEmptySession () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->otp->checkSMS('999999999');
	}

	public function testCheckSMSEmptyUsernameIsUsernameEmpty () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->otp->checkSMS('999999999');
	}

	public function testVerifyEmailValidEmail () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(false));
		$this
			->otp
			->method('fetch')
			->will($this->returnValue('test'));

		$this->assertSame($this->otp, $this->otp->verifyEmail('email@email.com', 'code'));
	}

	public function testVerifyEmailEmptySession () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->otp->verifyEmail('email@email.com', 'code');
	}

	public function testVerifyEmailEmptyUsernameIsUsernameEmpty () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->otp->verifyEmail('email@email.com', 'code');
	}

	public function testVerifySMSValidPhone () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(false));
		$this
			->otp
			->method('fetch')
			->will($this->returnValue('test'));

		$this->assertSame($this->otp, $this->otp->verifySMS('9999999999', 'code'));
	}

	public function testVerifySMSEmptySession () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->otp->verifySMS('999999999', 'code');
	}

	public function testVerifySMSEmptyUsernameIsUsernameEmpty () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->otp->verifySMS('999999999', 'code');
	}

	public function testVerifiedEmailValidUsername () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(false));
		$this
			->otp
			->method('fetch')
			->will($this->returnValue(['state' => true]));

		$this->assertTrue($this->otp->verifiedEmail('username'));
	}

	public function testVerifiedEmailEmptySession () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->otp->verifiedEmail('username');
	}

	public function testVerifiedEmailEmptyUsernameIsUsernameEmpty () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->otp->verifiedEmail();
	}

	public function testVerifiedEmailEmptyUsernameIsUsernameEmptyFalse () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('getUsername')
			->will($this->returnValue('username'));
		$this
			->otp
			->method('fetch')
			->will($this->returnValue(['state'=> true]));

		$this->assertTrue($this->otp->verifiedEmail());

	}
	public function testVerifiedEmailInvalidUsername () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(false));

		$this->setExpectedException('Veridu\API\Exception\InvalidUsername');
		$this->otp->verifiedEmail('@123#');
	}

	public function testVerifiedSMSValidUsername () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(false));
		$this
			->otp
			->method('fetch')
			->will($this->returnValue(['state' => true]));

		$this->assertTrue($this->otp->verifiedSMS('username'));
	}

	public function testVerifiedSMSEmptySession () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->otp->verifiedSMS('username');
	}

	public function testVerifiedSMSEmptyUsernameIsUsernameEmptyFalse () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('getUsername')
			->will($this->returnValue('username'));
		$this
			->otp
			->method('fetch')
			->will($this->returnValue(['state'=> true]));

		$this->assertTrue($this->otp->verifiedSMS());
	}

	public function testVerifiedSMSEmptyUsernameIsUsernameEmpty () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->otp->verifiedEmail();
	}

	public function testVerifiedSMSInvalidUsername () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(false));

		$this->setExpectedException('Veridu\API\Exception\InvalidUsername');
		$this->otp->verifiedSMS('@123#');
	}
}

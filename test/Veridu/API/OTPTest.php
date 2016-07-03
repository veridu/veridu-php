<?php

namespace Veridu\API;

use Veridu\API\OTP;

class OTPTest extends \PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
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
        $this->otp = $this
            ->getMockBuilder('Veridu\API\OTP')
            ->setConstructorArgs([$this->config['key'], $this->config['secret'], $this->config['version'], &$this->client, &$this->signature, &$this->storage])
            ->setMethods(['setKey', 'setSecret', 'setVersion', 'fetch', 'signedFetch'])
            ->getMock();
    }

    public function testListAllReturnsArray()
    {
        $this
            ->storage
            ->method('getUsername')
            ->will($this->returnValue('username'));
        $this
            ->otp
            ->method('fetch')
            ->will($this->returnValue(
                [
                    'list' => [
                        'test' => 'test'
                    ]
                ]
            ));
        $this->assertSame(['test' => 'test'], $this->otp->listAll('username'));
    }

    public function testListAllThrowsEmptySession()
    {
        $this
            ->storage
            ->method('isSessionEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptySession');
        $this->otp->listAll('username');
    }

    public function testListAllThrowsEmptyUsername()
    {
        $this
            ->storage
            ->method('isUsernameEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptyUsername');
        $this->otp->listAll();
    }

    public function testListAllThrowsInvalidUsername()
    {
        $this->setExpectedException('Veridu\API\Exception\InvalidUsername');
        $this->otp->listAll('@123#');
    }

    public function testCreateEmailReturnsArray()
    {
        $this
            ->storage
            ->method('getUsername')
            ->will($this->returnValue('username'));
        $this
            ->otp
            ->method('fetch')
            ->will($this->returnValue(
                [
                'test' => 'test'
                ]
            ));
        $this->assertSame(['test' => 'test'], $this->otp->createEmail('email', 'extended', 'callbackURL'));
    }

    public function testCreateEmailThrowsEmptySession()
    {
        $this
            ->storage
            ->method('isSessionEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptySession');
        $this->otp->createEmail('email', 'extended', 'callbackURL');
    }

    public function testCreateEmailThrowsEmptyUsername()
    {
        $this
            ->storage
            ->method('isUsernameEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptyUsername');
        $this->otp->createEmail('email', 'extended', 'callbackURL');
    }

    public function testCreateSMSReturnsArray()
    {
        $this
            ->storage
            ->method('getUsername')
            ->will($this->returnValue('username'));
        $this
            ->otp
            ->method('fetch')
            ->will($this->returnValue(
                [
                    'test' => 'test'
                ]
            ));
        $this->assertSame(['test' => 'test'], $this->otp->createSMS('+codeDDDnumber'));
    }

    public function testCreateSMSThrowsEmptySession()
    {
        $this
            ->storage
            ->method('isSessionEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptySession');
        $this->otp->createSMS('+codeDDDnumber');
    }

    public function testCreateSMSThrowsEmptyUsername()
    {
        $this
            ->storage
            ->method('isUsernameEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptyUsername');
        $this->otp->createSMS('+codeDDDnumber');
    }

    public function testResendEmailReturnsSelf()
    {
        $this
            ->storage
            ->method('getUsername')
            ->will($this->returnValue('username'));
        $this
            ->otp
            ->method('signedFetch')
            ->will($this->returnValue($this->otp));
        $this->assertSame($this->otp, $this->otp->resendEmail('email@email'));
    }

    public function testResendEmailThrowsEmptySession()
    {
        $this
            ->storage
            ->method('isSessionEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptySession');
        $this->otp->resendEmail('email@email');
    }

    public function testResendEmailThrowsEmptyUsername()
    {
        $this
            ->storage
            ->method('isUsernameEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptyUsername');
        $this->otp->resendEmail('email@email');
    }

    public function testResendSMSReturnsSelf()
    {
        $this
            ->storage
            ->method('getUsername')
            ->will($this->returnValue('username'));
        $this
            ->otp
            ->method('signedFetch')
            ->will($this->returnValue($this->otp));
        $this->assertSame($this->otp, $this->otp->resendSMS('+codeDDDnumber'));
    }

    public function testResendSMSThrowsEmptySession()
    {
        $this
            ->storage
            ->method('isSessionEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptySession');
        $this->otp->resendSMS('+codeDDDnumber');
    }

    public function testResendSMSThrowsEmptyUsername()
    {
        $this
            ->storage
            ->method('isUsernameEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptyUsername');
        $this->otp->resendSMS('+codeDDDnumber');
    }

    public function testCheckEmailReturnsBoolean()
    {
        $this
            ->storage
            ->method('getUsername')
            ->will($this->returnValue('username'));
        $this
            ->otp
            ->method('fetch')
            ->will($this->returnValue(['state' => true]));
        $this->assertSame(true, $this->otp->checkEmail('email@email'));
    }

    public function testCheckEmailThrowsEmptySession()
    {
        $this
            ->storage
            ->method('isSessionEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptySession');
        $this->otp->checkEmail('email@email');
    }

    public function testCheckEmailThrowsEmptyUsername()
    {
        $this
            ->storage
            ->method('isUsernameEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptyUsername');
        $this->otp->checkEmail('email@email');
    }

    public function testCheckSMSReturnsBoolean()
    {
        $this
            ->storage
            ->method('getUsername')
            ->will($this->returnValue('username'));
        $this
            ->otp
            ->method('fetch')
            ->will($this->returnValue(['state' => true]));
        $this->assertSame(true, $this->otp->checkSMS('+codeDDDnumber'));
    }

    public function testCheckSMSThrowsEmptySession()
    {
        $this
            ->storage
            ->method('isSessionEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptySession');
        $this->otp->checkSMS('+codeDDDnumber');
    }

    public function testCheckSMSThrowsEmptyUsername()
    {
        $this
            ->storage
            ->method('isUsernameEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptyUsername');
        $this->otp->checkSMS('+codeDDDnumber');
    }

    public function testVerifyEmailReturnsSelf()
    {
        $this
            ->storage
            ->method('getUsername')
            ->will($this->returnValue('username'));
        $this
            ->otp
            ->method('fetch')
            ->will($this->returnValue($this->otp));
        $this->assertSame($this->otp, $this->otp->verifyEmail('email@email', 'code'));
    }

    public function testVerifyEmailThrowsEmptySession()
    {
        $this
            ->storage
            ->method('isSessionEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptySession');
        $this->otp->verifyEmail('email@email', 'code');
    }

    public function testVerifyEmailThrowsEmptyUsername()
    {
        $this
            ->storage
            ->method('isUsernameEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptyUsername');
        $this->otp->verifyEmail('email@email', 'code');
    }

    public function testVerifySMSReturnsSelf()
    {
        $this
            ->storage
            ->method('getUsername')
            ->will($this->returnValue('username'));
        $this
            ->otp
            ->method('fetch')
            ->will($this->returnValue($this->otp));
        $this->assertSame($this->otp, $this->otp->verifySMS('+codeDDDnumber', 'code'));
    }

    public function testVerifySMSThrowsEmptySession()
    {
        $this
            ->storage
            ->method('isSessionEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptySession');
        $this->otp->verifySMS('+codeDDDnumber', 'code');
    }

    public function testVerifySMSThrowsEmptyUsername()
    {
        $this
            ->storage
            ->method('isUsernameEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptyUsername');
        $this->otp->verifySMS('+codeDDDnumber', 'code');
    }

    public function testVerifiedEmailReturnsBoolean()
    {
        $this
            ->storage
            ->method('getUsername')
            ->will($this->returnValue('username'));
        $this
            ->otp
            ->method('fetch')
            ->will($this->returnValue(['state' => true]));
        $this->assertSame(true, $this->otp->verifiedEmail());
    }

    public function testVerifiedEmailThrowsEmptySession()
    {
        $this
            ->storage
            ->method('isSessionEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptySession');
        $this->otp->verifiedEmail();
    }

    public function testVerifiedEmailThrowsEmptyUsername()
    {
        $this
            ->storage
            ->method('isUsernameEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptyUsername');
        $this->otp->verifiedEmail();
    }

    public function testVerifiedEmailThrowsInvalidUsername()
    {
        $this->setExpectedException('Veridu\API\Exception\InvalidUsername');
        $this->otp->verifiedEmail('@123#');
    }

    public function testVerifiedSMSReturnsBoolean()
    {
        $this
            ->storage
            ->method('getUsername')
            ->will($this->returnValue('username'));
        $this
            ->otp
            ->method('fetch')
            ->will($this->returnValue(['state' => true]));
        $this->assertSame(true, $this->otp->verifiedSMS());
    }

    public function testVerifiedSMSThrowsEmptySession()
    {
        $this
            ->storage
            ->method('isSessionEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptySession');
        $this->otp->verifiedSMS();
    }

    public function testVerifiedSMSThrowsEmptyUsername()
    {
        $this
            ->storage
            ->method('isUsernameEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptyUsername');
        $this->otp->verifiedSMS();
    }

    public function testVerifiedSMSThrowsInvalidUsername()
    {
        $this->setExpectedException('Veridu\API\Exception\InvalidUsername');
        $this->otp->verifiedSMS('@123#');
    }
}

<?php

namespace VeriduTest;

use Veridu\API\Provider;

class ProviderTest extends \PHPUnit_Framework_TestCase
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
        $this->signature = $this
            ->getMockBuilder('Veridu\Signature\SignatureInterface')
            ->getMock();
        $this->storage = $this
            ->getMockBuilder('Veridu\Storage\StorageInterface')
            ->setMethods(['isUsernameEmpty', 'getUsername', 'setSessionToken', 'getSessionToken', 'setSessionExpires', 'getSessionExpires', 'purgeSession', 'setUsername', 'isSessionEmpty', 'purgeUsername'])
            ->getMock();
        $this->provider = $this
            ->getMockBuilder('Veridu\API\Provider')
            ->setConstructorArgs([$this->config['key'], $this->config['secret'], $this->config['version'], &$this->client, &$this->signature, &$this->storage])
            ->setMethods(['setKey', 'setSecret', 'setVersion', 'fetch'])
            ->getMock();
    }

    public function testCreateOAuth1ReturnsInteger()
    {
        $this
            ->storage
            ->method('getUsername')
            ->will($this->returnValue('username'));
        $this
            ->provider
            ->method('fetch')
            ->will($this->returnValue(
                [
                    'task_id' => 'taskID',
                ]
            ));

        $this->assertSame('taskID', $this->provider->createOAuth1('provider', 'token', 'secret', 'appId'));
    }

    public function testCreateOAuth1ThrowsEmptySession()
    {
        $this
            ->storage
            ->method('isSessionEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptySession');
        $this->provider->createOAuth1('provider', 'token', 'secret', 'appId');
    }

    public function testCreateOAuth1ThrowsEmptyUsername()
    {
        $this
            ->storage
            ->method('isUsernameEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptyUsername');
        $this->provider->createOAuth1('provider', 'token', 'secret', 'appId');
    }

    public function testCreateOAuth2ReturnsInteger()
    {
        $this
            ->storage
            ->method('getUsername')
            ->will($this->returnValue('username'));
        $this
            ->provider
            ->method('fetch')
            ->will($this->returnValue(
                [
                    'task_id' => 'taskID',
                ]
            ));

        $this->assertSame('taskID', $this->provider->createOAuth2('provider', 'token', 'appId'));
    }

    public function testCreateOAuth2ThrowsEmptySession()
    {
        $this
            ->storage
            ->method('isSessionEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptySession');
        $this->provider->createOAuth2('provider', 'token', 'appId');
    }

    public function testCreateOAuth2ThrowsEmptyUsername()
    {
        $this
            ->storage
            ->method('isUsernameEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptyUsername');
        $this->provider->createOAuth2('provider', 'token', 'appId');
    }

    public function testGetAllDetailsReturnsArray()
    {
        $this
            ->provider
            ->method('fetch')
            ->will($this->returnValue(
                [
                'test' => 'test'
                ]
            ));

        $this->assertSame(['test' => 'test'], $this->provider->getAllDetails('username'));
    }

    public function testGetAllDetailsThrowsEmptySession()
    {
        $this
            ->storage
            ->method('isSessionEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptySession');
        $this->provider->getAllDetails('username');
    }

    public function testGetAllDetailsThrowsEmptyUsername()
    {
        $this
            ->storage
            ->method('isUsernameEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptyUsername');
        $this->provider->getAllDetails();
    }

    public function testGetAllDetailsThrowsInvalidUsername()
    {
        $this->setExpectedException('Veridu\API\Exception\InvalidUsername');
        $this->provider->getAllDetails('@123#');
    }

    public function testCheckReturnsBoolean()
    {
        $this
            ->provider
            ->method('fetch')
            ->will($this->returnValue(['state' => true]));
        $this->assertTrue($this->provider->check('provider', 'username'));
    }

    public function testCheckThrowsEmptySession()
    {
        $this
            ->storage
            ->method('isSessionEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptySession');
        $this->provider->check('provider', 'username');
    }

    public function testCheckThrowsEmptyUsername()
    {
        $this
            ->storage
            ->method('isUsernameEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptyUsername');
        $this->provider->check('provider');
    }

    public function testCheckThrowsInvalidUsername()
    {
        $this->setExpectedException('Veridu\API\Exception\InvalidUsername');
        $this->provider->check('provider', '@123#');
    }

    public function testListAllReturnsArray()
    {
        $this
            ->provider
            ->method('fetch')
            ->will($this->returnValue(
                [
                    'list' => [
                        'test' => 'test'
                    ]
                ]
            ));
        $this->assertSame(['test' => 'test'], $this->provider->listAll('username'));
    }

    public function testListAllThrowsInvalidUsername()
    {
        $this->setExpectedException('Veridu\API\Exception\InvalidUsername');
        $this->provider->listAll('@123#$');
    }

    public function testListAllThrowsEmptySession()
    {
        $this
            ->storage
            ->method('isSessionEmpty')
            ->will($this->returnValue(true));

        $this->setExpectedException('Veridu\API\Exception\EmptySession');
        $this->provider->listAll();
    }

    public function testListAllThrowsEmptyUsername()
    {
        $this
            ->storage
            ->method('isUsernameEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptyUsername');
        $this->provider->listAll();
    }
}

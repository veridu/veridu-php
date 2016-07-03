<?php

namespace VeriduTest;

use Veridu\API\SSO;
use Veridu\Storage\MemoryStorage;
use Veridu\Exception;
use Veridu\HTTPClient;

class SSOTest extends \PHPUnit_Framework_TestCase
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
        $this->sso = $this
            ->getMockBuilder('Veridu\API\SSO')
            ->setConstructorArgs([$this->config['key'], $this->config['secret'], $this->config['version'], &$this->client, &$this->signature, &$this->storage])
            ->setMethods(['setKey', 'setSecret', 'setVersion', 'signedFetch', 'fetch'])
            ->getMock();
    }

    public function testCreateOauth1ReturnsString()
    {
        $this
            ->storage
            ->method('getUsername')
            ->will($this->returnValue('username'));
        $this
            ->sso
            ->method('signedFetch')
            ->will($this->returnValue(
                [
                    'veridu_id' => 'test'
                ]
            ));
        $this->assertSame('test', $this->sso->createOauth1('provider', 'token', 'secret', 'mergeHash'));
    }

    public function testCreateOauth1ThrowsEmptySession()
    {
        $this
            ->storage
            ->method('isSessionEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptySession');
        $this->sso->createOauth1('provider', 'token', 'secret', 'mergeHash');
    }

    public function testCreateOauth2ReturnsString()
    {
        $this
            ->storage
            ->method('getUsername')
            ->will($this->returnValue('username'));
        $this
            ->sso
            ->method('signedFetch')
            ->will($this->returnValue(
                [
                    'veridu_id' => 'test'
                ]
            ));
        $this->assertSame('test', $this->sso->createOauth1('provider', 'token', 'refresh', 'mergeHash'));
    }

    public function testCreateOauth2ThrowsEmptySession()
    {
        $this
            ->storage
            ->method('isSessionEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptySession');
        $this->sso->createOauth1('provider', 'token', 'refresh', 'mergeHash');
    }
}

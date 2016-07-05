<?php

namespace VeriduTest;

use Veridu\API\Hook;
use Veridu\Storage\MemoryStorage;
use Veridu\Exception;
use Veridu\HTTPClient;

class HookTest extends \PHPUnit_Framework_TestCase
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
        $this->hook = $this
            ->getMockBuilder('Veridu\API\Hook')
            ->setConstructorArgs([$this->config['key'], $this->config['secret'], $this->config['version'], &$this->client, &$this->signature, &$this->storage])
            ->setMethods(['setKey', 'setSecret', 'setVersion', 'signedFetch', 'fetch'])
            ->getMock();
    }

    public function testCreateReturnsSelf()
    {
        $this
            ->storage
            ->method('getUsername')
            ->will($this->returnValue('username'));
        $this
            ->hook
            ->method('signedFetch')
            ->will($this->returnValue($this->hook));
        $this->assertSame($this->hook, $this->hook->create('trigger', 'callbackUrl'));
    }

    public function testCreateThrowsEmptySession()
    {
        $this
            ->storage
            ->method('isSessionEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptySession');
        $this->hook->create('trigger', 'callbackUrl');
    }

    public function testCreateThrowsEmptyUsername()
    {
        $this
            ->storage
            ->method('isUsernameEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptyUsername');
        $this->hook->create('trigger', 'callbackUrl');
    }

    public function testListAllReturnsArray()
    {
        $this
            ->storage
            ->method('getUsername')
            ->will($this->returnValue('username'));
        $this
            ->hook
            ->method('signedFetch')
            ->will($this->returnValue(
                [
                    'list' => [
                        'test' => 'test'
                    ]
                ]
            ));
        $this->assertSame(['test' => 'test'], $this->hook->listAll());
    }

    public function testListAllThrowsEmptySession()
    {
        $this
            ->storage
            ->method('isSessionEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptySession');
        $this->hook->listAll();
    }

    public function testListAllThrowsemptyUsername()
    {
        $this
            ->storage
            ->method('isUsernameEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptyUsername');
        $this->hook->listAll();
    }

    public function testDetailsReturnsArray()
    {
        $this
            ->storage
            ->method('getUsername')
            ->will($this->returnValue('username'));
        $this
            ->hook
            ->method('signedFetch')
            ->will($this->returnValue(
                [
                    'details' => [
                        'test' => 'test'
                    ]
                ]
            ));
        $this->assertSame(['test' => 'test'], $this->hook->details('hookId'));
    }

    public function testDetailsThrowEmptySession()
    {
        $this
            ->storage
            ->method('isSessionEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptySession');
        $this->hook->details('hookId');
    }

    public function testDetailsThrowEmptyUsername()
    {
        $this
            ->storage
            ->method('isUsernameEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptyUsername');
        $this->hook->details('hookId');
    }

    public function testDeleteReturnsArray()
    {
        $this
            ->storage
            ->method('getUsername')
            ->will($this->returnValue('username'));
        $this
            ->hook
            ->method('signedFetch')
            ->will($this->returnValue(
                [
                    'status' => [
                        'test' => 'test'
                    ]
                ]
            ));
        $this->assertSame(['test' => 'test'], $this->hook->delete('hookId'));
    }

    public function testDeleteThrowsEmptySession()
    {
        $this
            ->storage
            ->method('isSessionEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptySession');
        $this->hook->delete('hookId');
    }

    public function testDeleteThrowsEmptyUsername()
    {
        $this
            ->storage
            ->method('isUsernameEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptyUsername');
        $this->hook->delete('hookId');
    }
}

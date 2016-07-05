<?php

namespace VeriduTest;

use Veridu\API\Facts;
use Veridu\Storage\MemoryStorage;
use Veridu\Exception;
use Veridu\HTTPClient;

class FactsTest extends \PHPUnit_Framework_TestCase
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
        $this->facts = $this
            ->getMockBuilder('Veridu\API\Facts')
            ->setConstructorArgs([$this->config['key'], $this->config['secret'], $this->config['version'], &$this->client, &$this->signature, &$this->storage])
            ->setMethods(['setKey', 'setSecret', 'setVersion', 'signedFetch', 'fetch'])
            ->getMock();
    }

    public function testListAllReturnsArray()
    {
        $this
            ->storage
            ->method('getUsername')
            ->will($this->returnValue('username'));
        $this
            ->facts
            ->method('fetch')
            ->will($this->returnValue(
                [
                    'list' => [
                        'test' => 'test'
                    ]
                ]
            ));
        $this->assertSame(['test' => 'test'], $this->facts->listAll('username'));
    }

    public function testListAllThrowsEmptySession()
    {
        $this
            ->storage
            ->method('isSessionEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptySession');
        $this->facts->listAll('username');
    }

    public function testListAllThrowsEmptyUsername()
    {
        $this
            ->storage
            ->method('isUsernameEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptyUsername');
        $this->facts->listAll();
    }

    public function testListAllThrowsInvalidUsername()
    {
        $this->setExpectedException('Veridu\API\Exception\InvalidUsername');
        $this->facts->listAll('@123#');
    }

    public function testRetrieveReturnsArray()
    {
        $this
            ->storage
            ->method('getUsername')
            ->will($this->returnValue('username'));
        $this
            ->facts
            ->method('fetch')
            ->will($this->returnValue(
                [
                    'facts' => [
                        'test' => 'test'
                    ]
                ]
            ));
        $this->assertSame(['test' => 'test'], $this->facts->retrieve('provider', 'username'));
    }

    public function testRetrieveThrowsEmptySession()
    {
        $this
            ->storage
            ->method('isSessionEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptySession');
        $this->facts->retrieve('provider', 'username');
    }

    public function testRetrieveThrowsEmptyUsername()
    {
        $this
            ->storage
            ->method('isUsernameEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptyUsername');
        $this->facts->retrieve('provider');
    }

    public function testRetrieveThrowsInvalidUsername()
    {
        $this->setExpectedException('Veridu\API\Exception\InvalidUsername');
        $this->facts->retrieve('provider', '@123#');
    }
}

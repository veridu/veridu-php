<?php

namespace VeriduTest;

use Veridu\API\Badge;
use Veridu\Storage\MemoryStorage;
use Veridu\Exception;
use Veridu\HTTPClient;

class BadgeTest extends \PHPUnit_Framework_TestCase
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
        $this->badge = $this
            ->getMockBuilder('Veridu\API\Badge')
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
            ->badge
            ->method('signedFetch')
            ->will($this->returnValue($this->badge));
        $this->assertSame($this->badge, $this->badge->create('username', 'badge', 'timestamp', ['attributes' => 'none']));
    }

    public function testCreateThrowsEmptySession()
    {
        $this
            ->storage
            ->method('isSessionEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptySession');
        $this->badge->create('username', 'badge', 'timestamp', ['attributes' => 'none']);
    }

    public function testCreateThrowsEmptyUsername()
    {
        $this
            ->storage
            ->method('isUsernameEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptyUsername');
        $this->badge->create(null, 'badge', 'timestamp', ['attributes' => 'none']);
    }

    public function testCreateThrowsInvalidUsername()
    {
        $this->setExpectedException('Veridu\API\Exception\InvalidUsername');
        $this->badge->create('@123#', 'badge', 'timestamp', ['attributes' => 'none']);
    }

    public function testListAllReturnsArray()
    {
        $this
            ->storage
            ->method('getUsername')
            ->will($this->returnValue('username'));
        $this
            ->badge
            ->method('fetch')
            ->will($this->returnValue(
                [
                    'list' => [
                        'test' => 'test'
                    ]
                ]
            ));
        $this->assertSame(['test' => 'test'], $this->badge->listAll());
    }

    public function testListAllThrowsEmptySession()
    {
        $this
            ->storage
            ->method('isSessionEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptySession');
        $this->badge->listAll('username');
    }

    public function testListAllThrowsEmptyUsername()
    {
        $this
            ->storage
            ->method('isUsernameEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptyUsername');
        $this->badge->listAll();
    }

    public function testListAllThrowsInvalidUsername()
    {
        $this->setExpectedException('Veridu\API\Exception\InvalidUsername');
        $this->badge->listAll('@123#');
    }

    public function testDetailsReturnsArray()
    {
        $this
            ->storage
            ->method('getUsername')
            ->will($this->returnValue('username'));
        $this
            ->badge
            ->method('fetch')
            ->will($this->returnValue(
                [
                    'test' => 'test'
                ]
            ));
        $this->assertSame(['test' => 'test'], $this->badge->details('badge', 'state', 'username'));
    }

    public function testDetailsThrowsEmptySession()
    {
        $this
            ->storage
            ->method('isSessionEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptySession');
        $this->badge->details('badge', 'state', 'username');
    }

    public function testDetailsThrowsEmptyUsername()
    {
        $this
            ->storage
            ->method('isUsernameEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptyUsername');
        $this->badge->details('badge', 'state');
    }

    public function testDetailsThrowsInvalidUsername()
    {
        $this->setExpectedException('Veridu\API\Exception\InvalidUsername');
        $this->badge->details('badge', 'state', '@123#');
    }
}

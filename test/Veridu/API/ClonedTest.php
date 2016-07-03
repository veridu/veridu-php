<?php

namespace VeriduTest;

use Veridu\API\Cloned;
use Veridu\Storage\MemoryStorage;
use Veridu\Exception;
use Veridu\HTTPClient;

class ClonedTest extends \PHPUnit_Framework_TestCase
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
        $this->cloned = $this
            ->getMockBuilder('Veridu\API\Cloned')
            ->setConstructorArgs([$this->config['key'], $this->config['secret'], $this->config['version'], &$this->client, &$this->signature, &$this->storage])
            ->setMethods(['setKey', 'setSecret', 'setVersion', 'signedFetch', 'fetch'])
            ->getMock();
    }

    public function testDetailsReturnsArray()
    {
        $this
            ->storage
            ->method('getUsername')
            ->will($this->returnValue('username'));
        $this
            ->cloned
            ->method('signedFetch')
            ->will($this->returnValue(
                [
                    'clones' => [
                        'test' => 'test'
                    ]
                ]
            ));
        $this->assertSame(['test' => 'test'], $this->cloned->details('username'));
    }

    public function testDetailsThrowsEmptySession()
    {
        $this
            ->storage
            ->method('isSessionEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptySession');
        $this->cloned->details('username');
    }

    public function testDetailsThrowsEmptyUsername()
    {
        $this
            ->storage
            ->method('isUsernameEmpty')
            ->will($this->returnValue(true));
        $this->setExpectedException('Veridu\API\Exception\EmptyUsername');
        $this->cloned->details();
    }

    public function testDetailsThrowsInvalidUsername()
    {
        $this->setExpectedException('Veridu\API\Exception\InvalidUsername');
        $this->cloned->details('@123#');
    }
}

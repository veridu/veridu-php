<?php

namespace VeriduTest;

use Veridu\API\AbstractEndpoint;
use Veridu\Signature\SignatureInterface;
use Veridu\Storage\StorageInterface;
use Veridu\Storage\MemoryStorage;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Veridu\Signature\HMAC;

class AbstractEndpointTest extends \PHPUnit_Framework_TestCase
{
    protected $endpoint = null;
    protected $client = null;
    protected $signature = null;
    protected $storage = null;
    protected $config = null;

    protected function setUp()
    {
        $this->config = [
            'key' => 'key',
            'secret' => 'secret',
            'version' => 'version'
        ];
    }
    protected function fetchMethodWithOrWithoutData($methodParam, $hasData)
    {
        $this->client = $this
            ->getMockBuilder('\GuzzleHttp\Client')
            ->setMethods([$methodParam])
            ->getMock();
        $response = $this
            ->getMockBuilder('\GuzzleHttp\Psr7\Response')
            ->setMethods(['getBody'])
            ->getMock();
        $response
            ->method('getBody')
            ->will($this->returnValue(json_encode([
                'status' =>  'test'
                ])));
        $this
            ->client->method($methodParam)
            ->will($this->returnValue($response));
        $this->signature = $this
            ->getMockBuilder('Veridu\Signature\SignatureInterface')
            ->getMock();
        $this->storage = new MemoryStorage();
        $this->endpoint = $this
            ->getMockBuilder('Veridu\API\AbstractEndpoint')
            ->setConstructorArgs([$this->config['key'], $this->config['secret'], $this->config['version'], &$this->client, &$this->signature, &$this->storage])
            ->setMethods(['setKey', 'setSecret', 'setVersion', 'setSignature'])
            ->getMockForAbstractClass();

        if ($hasData) {
            $method = new \ReflectionMethod('\Veridu\API\AbstractEndpoint', 'fetch');
            $method->setAccessible(true);
            $result = $method->invoke($this->endpoint, $methodParam, 'test', 'data');
            $this->assertSame(['status' => 'test'], $result);
        } else {
            $method = new \ReflectionMethod('\Veridu\API\AbstractEndpoint', 'fetch');
            $method->setAccessible(true);
            $result = $method->invoke($this->endpoint, $methodParam, 'test');
            $this->assertSame(['status' => 'test'], $result);
        }
    }

    public function testFetchInvalidMethod()
    {
        $this->client = $this
            ->getMockBuilder('\GuzzleHttp\Client')
            ->getMock();
        $response = $this
            ->getMockBuilder('\GuzzleHttp\Psr7\Response')
            ->getMock();
        $this->signature = $this
            ->getMockBuilder('Veridu\Signature\SignatureInterface')
            ->getMock();
        $this->storage = new MemoryStorage();
        $this->endpoint = $this
            ->getMockBuilder('Veridu\API\AbstractEndpoint')
            ->setConstructorArgs([
                $this->config['key'],
                $this->config['secret'],
                $this->config['version'],
                &$this->client,
                &$this->signature,
                &$this->storage
            ])
            ->setMethods(['setKey', 'setSecret', 'setVersion', 'setSignature'])
            ->getMockForAbstractClass();

        $method = new \ReflectionMethod('\Veridu\API\AbstractEndpoint', 'fetch');
        $method->setAccessible(true);
        $this->setExpectedException('Veridu\API\Exception\InvalidMethod');
        $method->invoke($this->endpoint, 'invalid', 'test');
    }

    public function testFetchGETWithData()
    {
        $this->fetchMethodWithOrWithoutData('get', true);
    }

    public function testFetchGETWithoutData()
    {
        // THE SAME AS testFetchWithSession();
        $this->fetchMethodWithOrWithoutData('get', false);
    }

    public function testFetchPOSTWithData()
    {
        $this->fetchMethodWithOrWithoutData('post', true);
    }

    public function testFetchPOSTWithoutData()
    {
        $this->fetchMethodWithOrWithoutData('post', false);
    }

    public function testFetchDELETEWithData()
    {
        $this->fetchMethodWithOrWithoutData('delete', true);
    }

    public function testFetchDELETEWithoutData()
    {
        $this->fetchMethodWithOrWithoutData('delete', false);
    }

    public function testFetchPUTWithData()
    {
        $this->fetchMethodWithOrWithoutData('put', true);
    }

    public function testFetchPUTWithoutData()
    {
        $this->fetchMethodWithOrWithoutData('put', false);
    }

    public function testFetchWithInvalidResponseFormat()
    {
        $this->client = $this
            ->getMockBuilder('\GuzzleHttp\Client')
            ->setMethods(['post'])
            ->getMock();
        $response = $this
            ->getMockBuilder('\GuzzleHttp\Psr7\Response')
            ->setMethods(['getBody'])
            ->getMock();
        $response
            ->method('getBody')
            ->will($this->returnValue(null));
        $this->client
            ->method('post')
            ->will($this->returnValue($response));
        $this->signature = $this
            ->getMockBuilder('Veridu\Signature\SignatureInterface')
            ->getMock();
        $this->storage = new MemoryStorage();
        $this->endpoint = $this
            ->getMockBuilder('Veridu\API\AbstractEndpoint')
            ->setConstructorArgs([
                $this->config['key'],
                $this->config['secret'],
                $this->config['version'],
                &$this->client,
                &$this->signature,
                &$this->storage
            ])
            ->setMethods(['setKey', 'setSecret', 'setVersion', 'setSignature'])
            ->getMockForAbstractClass();

        $method = new \ReflectionMethod('\Veridu\API\AbstractEndpoint', 'fetch');
        $method->setAccessible(true);
        $this->setExpectedException('Veridu\API\Exception\InvalidFormat');
        $method->invoke($this->endpoint, 'post', 'test');
    }

    public function testFetchWithInvalidResponse()
    {
        $this->client = $this
            ->getMockBuilder('\GuzzleHttp\Client')
            ->setMethods(['get'])
            ->getMock();
        $response = $this
            ->getMockBuilder('\GuzzleHttp\Psr7\Response')
            ->setMethods(['getBody'])
            ->getMock();
        $response
            ->method('getBody')
            ->will($this->returnValue(json_encode([
                'dumb' =>  'test'
            ])));
        $this->client
            ->method('get')
            ->will($this->returnValue($response));
        $this->signature = $this
            ->getMockBuilder('Veridu\Signature\SignatureInterface')
            ->getMock();
        $this->storage = new MemoryStorage();
        $this->endpoint = $this
            ->getMockBuilder('Veridu\API\AbstractEndpoint')
            ->setConstructorArgs([
                $this->config['key'],
                $this->config['secret'],
                $this->config['version'],
                &$this->client,
                &$this->signature,
                &$this->storage
            ])
            ->setMethods(['setKey', 'setSecret', 'setVersion', 'setSignature'])
            ->getMockForAbstractClass();

        $method = new \ReflectionMethod('\Veridu\API\AbstractEndpoint', 'fetch');
        $method->setAccessible(true);
        $this->setExpectedException('Veridu\API\Exception\InvalidResponse');
        $method->invoke($this->endpoint, 'get', 'test');
    }

    public function testFetchWithAPIError()
    {
        $this->client = $this
            ->getMockBuilder('\GuzzleHttp\Client')
            ->setMethods(['get'])
            ->getMock();
        $response = $this
            ->getMockBuilder('\GuzzleHttp\Psr7\Response')
            ->setMethods(['getBody'])
            ->getMock();
        $response
            ->method('getBody')
            ->will($this->returnValue(json_encode(
                [
                    'status' => false,
                    'error' => [
                        'type' => 'testingType',
                        'message'=> 'testingMessage'
                    ]
                ])
            ));
        $this->client
            ->method('get')
            ->will($this->returnValue($response));
        $this->signature = $this
            ->getMockBuilder('Veridu\Signature\SignatureInterface')
            ->getMock();
        $this->storage = new MemoryStorage();
        $this->endpoint = $this
            ->getMockBuilder('Veridu\API\AbstractEndpoint')
            ->setConstructorArgs([
                $this->config['key'],
                $this->config['secret'],
                $this->config['version'],
                &$this->client,
                &$this->signature,
                &$this->storage
            ])
            ->setMethods(['setKey', 'setSecret', 'setVersion', 'setSignature'])
            ->getMockForAbstractClass();

        $method = new \ReflectionMethod('\Veridu\API\AbstractEndpoint', 'fetch');
        $method->setAccessible(true);
        $this->setExpectedException('Veridu\API\Exception\APIError');
        $method->invoke($this->endpoint, 'get', 'test');
    }

    public function testsignedFetchWithData()
    {
        $this->client = $this
            ->getMockBuilder('\GuzzleHttp\Client')
            ->setMethods(['get'])
            ->getMock();
        $this->signature = $this
            ->getMockBuilder('Veridu\Signature\HMAC')
            ->setMethods(['sign', 'lastNonce'])
            ->getMock();
        $this
            ->signature
            ->method('sign')
            ->will($this->returnValue([
                'client' => null,
                'hash' => 'sha1',
                'method' => 'GET',
                'nonce' => 'nonce',
                'resource' => 'https://api.veridu.com/test',
                'timestamp' => 123456,
                'version' => null,
                'signature' => 'signature'
            ]));
        $this
            ->signature
            ->method('lastNonce')
            ->will($this->returnValue('nonce'));
        $this->storage = new MemoryStorage();
        $this->endpoint = $this
            ->getMockBuilder('Veridu\API\AbstractEndpoint')
            ->setConstructorArgs([
                $this->config['key'],
                $this->config['secret'],
                $this->config['version'],
                &$this->client,
                &$this->signature,
                &$this->storage
            ])
            ->setMethods(['setKey', 'setSecret', 'setVersion', 'fetch'])
            ->getMockForAbstractClass();
        $this
            ->endpoint
            ->method('fetch')
            ->will($this->returnValue([
                'nonce' => 'nonce',
                'test' => 'test'
                ]));

        $method = new \ReflectionMethod('\Veridu\API\AbstractEndpoint', 'signedFetch');
        $method->setAccessible(true);
        $result = $method->invoke($this->endpoint, 'get', 'test', $this->config);
        $this->assertEquals(['test' => 'test'], $result);
    }

    public function testsignedFetchWithoutData()
    {
        $this->client = $this
            ->getMockBuilder('\GuzzleHttp\Client')
            ->setMethods(['get'])
            ->getMock();
        $this->signature = $this
            ->getMockBuilder('Veridu\Signature\HMAC')
            ->setMethods(['sign', 'lastNonce'])
            ->getMock();
        $this
            ->signature
            ->method('sign')
            ->will($this->returnValue([
                'client' => null,
                'hash' => 'sha1',
                'method' => 'GET',
                'nonce' => 'nonce',
                'resource' => 'https://api.veridu.com/test',
                'timestamp' => 123456,
                'version' => null,
                'signature' => 'signature'
            ]));
        $this
            ->signature
            ->method('lastNonce')
            ->will($this->returnValue('nonce'));
        $this->storage = new MemoryStorage();
        $this->endpoint = $this
            ->getMockBuilder('Veridu\API\AbstractEndpoint')
            ->setConstructorArgs([
                $this->config['key'],
                $this->config['secret'],
                $this->config['version'],
                &$this->client,
                &$this->signature,
                &$this->storage
            ])
            ->setMethods(['setKey', 'setSecret', 'setVersion', 'fetch'])
            ->getMockForAbstractClass();
        $this
            ->endpoint
            ->method('fetch')
            ->will($this->returnValue([
                'nonce' => 'nonce',
                'test' => 'test'
                ]));

        $method = new \ReflectionMethod('\Veridu\API\AbstractEndpoint', 'signedFetch');
        $method->setAccessible(true);
        $result = $method->invoke($this->endpoint, 'get', 'test');
        $this->assertEquals(['test' => 'test'], $result);
    }

    public function testsignedFetchWithNonceMismatch()
    {
        $this->client = $this
            ->getMockBuilder('\GuzzleHttp\Client')
            ->setMethods(['get'])
            ->getMock();
        $this->signature = $this
            ->getMockBuilder('Veridu\Signature\HMAC')
            ->setMethods(['sign', 'lastNonce'])
            ->getMock();
        $this
            ->signature
            ->method('sign')
            ->will($this->returnValue([
                'client' => null,
                'hash' => 'sha1',
                'method' => 'GET',
                'nonce' => 'nonce',
                'resource' => 'https://api.veridu.com/test',
                'timestamp' => 123456,
                'version' => null,
                'signature' => 'signature'
            ]));
        $this
            ->signature
            ->method('lastNonce')
            ->will($this->returnValue('nonce'));
        $this->storage = new MemoryStorage();
        $this->endpoint = $this
            ->getMockBuilder('Veridu\API\AbstractEndpoint')
            ->setConstructorArgs([
                $this->config['key'],
                $this->config['secret'],
                $this->config['version'],
                &$this->client,
                &$this->signature,
                &$this->storage
            ])
            ->setMethods(['setKey', 'setSecret', 'setVersion', 'fetch'])
            ->getMockForAbstractClass();

        $method = new \ReflectionMethod('\Veridu\API\AbstractEndpoint', 'signedFetch');
        $method->setAccessible(true);
        $this->setExpectedException('Veridu\Signature\Exception\NonceMismatch');
        $method->invoke($this->endpoint, 'get', 'test');
    }

    public function testSetAndGetClient()
    {
        $this->signature = $this
            ->getMockBuilder('Veridu\Signature\SignatureInterface')
            ->getMock();
        $this->storage = new MemoryStorage();
        $this->client = $this
            ->getMockBuilder('\GuzzleHttp\Client')
            ->getMock();
        $this->endpoint = $this
            ->getMockBuilder('Veridu\API\AbstractEndpoint')
            ->setConstructorArgs([$this->config['key'], $this->config['secret'], $this->config['version'], &$this->client, &$this->signature, &$this->storage])
            ->setMethods(['setKey', 'setSecret', 'setVersion', 'setSignature', 'setStorage'])
            ->getMockForAbstractClass();

        $method = new \ReflectionMethod('\Veridu\API\AbstractEndpoint', 'getClient');
        $method->setAccessible(true);
        $result = $method->invoke($this->endpoint);
        $this->assertInstanceOf('GuzzleHttp\Client', $result);
    }

    public function testSetAndGetSignature()
    {
        $this->signature = $this
            ->getMockBuilder('Veridu\Signature\SignatureInterface')
            ->getMock();
        $this->storage = new MemoryStorage();
        $this->client = $this
            ->getMockBuilder('\GuzzleHttp\Client')
            ->getMock();
        $this->endpoint = $this
            ->getMockBuilder('Veridu\API\AbstractEndpoint')
            ->setConstructorArgs([$this->config['key'], $this->config['secret'], $this->config['version'], &$this->client, &$this->signature, &$this->storage])
            ->setMethods(['setKey', 'setSecret', 'setVersion', 'setClient', 'setStorage'])
            ->getMockForAbstractClass();

        $method = new \ReflectionMethod('\Veridu\API\AbstractEndpoint', 'getSignature');
        $method->setAccessible(true);
        $result = $method->invoke($this->endpoint);
        $this->assertInstanceOf('Veridu\Signature\SignatureInterface', $result);
    }

    public function testSetAndGetStorage()
    {
        $this->signature = $this
            ->getMockBuilder('Veridu\Signature\SignatureInterface')
            ->getMock();
        $this->storage = new MemoryStorage();
        $this->client = $this
            ->getMockBuilder('\GuzzleHttp\Client')
            ->getMock();
        $this->endpoint = $this
            ->getMockBuilder('Veridu\API\AbstractEndpoint')
            ->setConstructorArgs([$this->config['key'], $this->config['secret'], $this->config['version'], &$this->client, &$this->signature, &$this->storage])
            ->setMethods(['setKey', 'setSecret', 'setVersion', 'setClient', 'setSignature'])
            ->getMockForAbstractClass();

        $method = new \ReflectionMethod('\Veridu\API\AbstractEndpoint', 'getStorage');
        $method->setAccessible(true);
        $result = $method->invoke($this->endpoint);
        $this->assertInstanceOf('Veridu\Storage\StorageInterface', $result);
    }

    public function testlastError()
    {
        $this->signature = $this
            ->getMockBuilder('Veridu\Signature\SignatureInterface')
            ->getMock();
        $this->storage = new MemoryStorage();
        $this->client = $this
            ->getMockBuilder('\GuzzleHttp\Client')
            ->getMock();
        $this->endpoint = $this
            ->getMockBuilder('Veridu\API\AbstractEndpoint')
            ->setConstructorArgs([$this->config['key'], $this->config['secret'], $this->config['version'], &$this->client, &$this->signature, &$this->storage])
            ->setMethods(['setKey', 'setSecret', 'setVersion', 'setClient', 'setSignature', 'setStorage'])
            ->getMockForAbstractClass();

        $method = new \ReflectionMethod('\Veridu\API\AbstractEndpoint', 'lastError');
        $method->setAccessible(true);
        $result = $method->invoke($this->endpoint);
        $this->assertNull($result);
    }
}

<?php

namespace VeriduTest;

use Veridu\API\Task;

class TaskTest extends \PHPUnit_Framework_TestCase {
	protected $task = null;
	protected $client = null;
	protected $signature = null;
	protected $storage = null;
	protected $config = null;

	protected function setUp () {
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
		$this->task = $this
			->getMockBuilder('Veridu\API\Task')
			->setConstructorArgs([$this->config['key'], $this->config['secret'], $this->config['version'], &$this->client, &$this->signature, &$this->storage])
			->setMethods(['setKey', 'setSecret', 'setVersion', 'setSignature', 'fetch'])
			->getMockForAbstractClass();
	}

	public function testListAll () {
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
			->task
			->method('fetch')
			->will($this->returnValue(['list'=>'test']));

		$this->assertSame('test', $this->task->listAll());
	}

	public function testListAllEmptySession () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->task->listAll();
	}

	public function testListAllisUsernameEmpty () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->task->listAll();
	}

	public function testDetailsValidTaskId () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(false));
		$this
			->task
			->method('fetch')
			->will($this->returnValue([
				'info' => [
					'running' => true,
					'status' => true,
					'message' => ''
				]
			]));

		$this->assertSame([
			'running' => true,
			'status' => true,
			'message' => ''
			], $this->task->details('task_id'));
	}

	public function testDetailsEmptySession () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->task->details('task_id');
	}

	public function testDetailsEmptyUsernameIsUsernameEmpty () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->task->details('task_id');
	}
}

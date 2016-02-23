<?php

namespace VeriduTest;

use Veridu\API\User;

class UserTest extends \PHPUnit_Framework_TestCase {

	public function setUp () {
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
		$this->user = $this
			->getMockBuilder('Veridu\API\User')
			->setConstructorArgs([$this->config['key'], $this->config['secret'], $this->config['version'], &$this->client, &$this->signature, &$this->storage])
			->setMethods(['setKey', 'setSecret', 'setVersion', 'setSignature', 'signedFetch', 'fetch'])
			->getMock();
	}

	public function genericUsername ($originalMethod, $typeofTest) {
		switch($typeofTest) {
			case 'ValidUsername':
				$this
					->storage
					->method('isSessionEmpty')
					->will($this->returnValue(false));
				$this
					->user
					->method('fetch')
					->will($this->returnValue(['profile' => 'test']));

				$this->assertSame('test', $this->user->$originalMethod('username'));
			break;
			case 'EmptySession':
				$this
					->storage
					->method('isSessionEmpty')
					->will($this->returnValue(true));

				$this->setExpectedException('Veridu\API\Exception\EmptySession');
				$this->user->$originalMethod('username');
			break;
			case 'EmptyUsernameIsEmptyUsername':
				$this
					->storage
					->method('isSessionEmpty')
					->will($this->returnValue(false));
				$this
					->storage
					->method('isUsernameEmpty')
					->will($this->returnValue(true));

				$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
				$this->user->$originalMethod();
			break;
			case 'EmptyUsernameFalse':
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
					->user
					->method('fetch')
					->will($this->returnValue(['profile' => 'test']));

				$this->assertSame('test', $this->user->$originalMethod('username'));
			break;
			case 'InvalidUsername':
				$this
					->storage
					->method('isSessionEmpty')
					->will($this->returnValue(false));

				$this->setExpectedException('Veridu\API\Exception\InvalidUsername');
				$this->user->$originalMethod('@123#');
			break;
			default:
				printf('Error: no method or test found');
			break;
		}
	}

	public function genericAttributes ($originalMethod, $typeofTest) {
		switch($typeofTest) {
			case 'ValidUsername':
				$this
					->storage
					->method('isSessionEmpty')
					->will($this->returnValue(false));
				$this
					->user
					->method('fetch')
					->will($this->returnValue(['attribute' => 'test']));

				$this->assertSame('test', $this->user->$originalMethod('attributes', 'username'));
			break;
			case 'EmptySession':
				$this
					->storage
					->method('isSessionEmpty')
					->will($this->returnValue(true));

				$this->setExpectedException('Veridu\API\Exception\EmptySession');
				$this->user->$originalMethod('attributes', 'username');
			break;
			case 'EmptyUsernameIsEmptyUsername':
				$this
					->storage
					->method('isSessionEmpty')
					->will($this->returnValue(false));
				$this
					->storage
					->method('isUsernameEmpty')
					->will($this->returnValue(true));

				$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
				$this->user->$originalMethod('attributes');
			break;
			case 'EmptyUsername':
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
					->user
					->method('fetch')
					->will($this->returnValue(['attribute' => 'test']));

				$this->assertSame('test', $this->user->$originalMethod('attributes', 'username'));
			break;
			case 'InvalidUsername':
				$this
					->storage
					->method('isSessionEmpty')
					->will($this->returnValue(false));

				$this->setExpectedException('Veridu\API\Exception\InvalidUsername');
				$this->user->$originalMethod('attributes', '@123#');
			break;
			default:
				printf('Error: no method or test found');
			break;
		}
	}

	public function testCreateEmptyUsernameisUsernameEmptyFalse () {
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
			->will($this->returnValue(''));
		$this
			->user
			->method('signedFetch')
			->will($this->returnValue('test'));
		$this
			->storage
			->method('setUsername')
			->will($this->returnValue('test'));

		$this->assertSame($this->user, $this->user->create('username'));
	}

	public function testCreateInvalidUsername () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));

		$this->setExpectedException('Veridu\API\Exception\InvalidUsername');
		$this->user->create('@123#');
	}

	public function testCreateEmptySession () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->user->create('username');
	}

	public function testCreateUsernameNotEmpty () {
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

		$this->assertSame($this->user, $this->user->create('username'));
	}

	public function testGetAllDetailsValidUsername () {
		$this->genericUsername('getAllDetails', 'ValidUsername');
	}

	public function testGetAllDetailsEmptySession () {
		$this->genericUsername('getAllDetails', 'EmptySession');
	}

	public function testGetAllDetailsEmptyUsernameIsEmptyUsername () {
		$this->genericUsername('getAllDetails', 'EmptyUsernameIsEmptyUsername');
	}

	public function testGetAllDetailsEmptyUsernameIsEmptyUsernameFalse () {
		$this->genericUsername('getAllDetails', 'EmptyUsernameFalse');

	}

	public function testGetAllDetailsInvalidUsername () {
		$this->genericUsername('getAllDetails', 'InvalidUsername');
	}

	public function testGetAllAttributeScoresValidUsername () {
		$this->genericUsername('getAllAttributeScores', 'ValidUsername');
	}

	public function testGetAllAttributeScoresEmptySession () {
		$this->genericUsername('getAllAttributeScores', 'EmptySession');
	}

	public function testGetAllAttributeScoresEmptyUsernameIsEmptyUsername () {
		$this->genericUsername('getAllAttributeScores', 'EmptyUsernameIsEmptyUsername');
	}

	public function testGetAllAttributeScoresEmptyUsernameIsEmptyUsernameFalse () {
		$this->genericUsername('getAllAttributeScores', 'EmptyUsernameFalse');
	}

	public function testGetAllAttributeScoresInvalidUsername () {
		$this->genericUsername('getAllAttributeScores', 'InvalidUsername');
	}

	public function testGetAllAttributeValuesValidUsername () {
		$this->genericUsername('getAllAttributeValues', 'ValidUsername');
	}

	public function testGetAllAttributeValuesEmptySession () {
		$this->genericUsername('getAllAttributeValues', 'EmptySession');
	}

	public function testGetAllAttributeValuesEmptyUsernameIsEmptyUsername () {
		$this->genericUsername('getAllAttributeValues', 'EmptyUsernameIsEmptyUsername');
	}

	public function testGetAllAttributeValuesEmptyUsernameisEmptyUsernameFalse () {
		$this->genericUsername('getAllAttributeValues', 'EmptyUsernameFalse');
	}

	public function testGetAllAttributeValuesInvalidUsername () {
		$this->genericUsername('getAllAttributeValues', 'InvalidUsername');
	}

	public function testAttributeDetailsValidUsername () {
		$this->genericAttributes('attributeDetails', 'ValidUsername');
	}

	public function testAttributeDetailsEmptySession () {
		$this->genericAttributes('attributeDetails', 'EmptySession');
	}

	public function testAttributeDetailsEmptyUsernameIsEmptyUsername () {
		$this->genericAttributes('attributeDetails', 'EmptyUsernameIsEmptyUsername');
	}

	public function testAttributeDetailsEmptyUsernameIsEmptyUsernameFalse () {
		$this->genericAttributes('attributeDetails', 'EmptyUsernameFalse');
	}

	public function testAttributeDetailsInvalidUsername () {
		$this->genericAttributes('attributeDetails', 'InvalidUsername');
	}

	public function testAttributeScoreValidUsername () {
		$this->genericAttributes('attributeScore', 'ValidUsername');
	}

	public function testAttributeScoreEmptySession () {
		$this->genericAttributes('attributeScore', 'EmptySession');
	}

	public function testAttributeScoreEmptyUsernameIsEmptyUsername () {
		$this->genericAttributes('attributeScore', 'EmptyUsernameIsEmptyUsername');
	}

	public function testAttributeScoreEmptyUsernameisEmptyUsernameFalse () {
		$this->genericAttributes('attributeScore', 'EmptyUsernameFalse');
	}

	public function testAttributeScoreInvalidUsername () {
		$this->genericAttributes('attributeScore', 'InvalidUsername');
	}

	public function testAttributeValueValidUsername () {
		$this->genericAttributes('attributeValue', 'ValidUsername');
	}

	public function testAttributeValueEmptySession () {
		$this->genericAttributes('attributeValue', 'EmptySession');
	}

	public function testAttributeValueEmptyUsernameIsEmptyUsername () {
		$this->genericAttributes('attributeValue', 'EmptyUsernameIsEmptyUsername');
	}

	public function testAttributeValueEmptyUsernameIsEmptyUsernameFalse () {
		$this->genericAttributes('attributeValue', 'EmptyUsernameFalse');
	}

	public function testAttributeValueInvalidUsername () {
		$this->genericAttributes('attributeValue', 'InvalidUsername');
	}

	public function testCompareAttributeValidUsername () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->user
			->method('fetch')
			->will($this->returnValue('test'));

		$this->assertSame('test', $this->user->compareAttribute('attributes', 'value', 'username'));
	}

	public function testCompareAttributeEmptySession () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptySession');
		$this->user->compareAttribute('attributes', 'value', 'username');
	}

	public function testCompareAttributeEmptyUsernameIsEmptyUsername () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));
		$this
			->storage
			->method('isUsernameEmpty')
			->will($this->returnValue(true));

		$this->setExpectedException('Veridu\API\Exception\EmptyUsername');
		$this->user->compareAttribute('attributes', 'values');
	}

	public function  testCompareAttributeEmptyUsernameIsEmptyUsernameFalse() {
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
			->user
			->method('fetch')
			->will($this->returnValue('test'));

		$this->assertSame('test', $this->user->compareAttribute('attributes', 'value', 'username'));
	}

	public function testCompareAttributeInvalidUsername () {
		$this
			->storage
			->method('isSessionEmpty')
			->will($this->returnValue(false));

		$this->setExpectedException('Veridu\API\Exception\InvalidUsername');
		$this->user->compareAttribute('attributes', 'value', '@123#');
	}
}
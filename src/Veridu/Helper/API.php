<?php

namespace Veridu\Helper;

use Veridu\API as APICore;
use Veridu\API\Check;

final class API {

	private $api;

	public static function factory($key, $secret, $version = '0.3', StorageInterface $storage = null, SignatureInterface $signature = null) {
		return new APIHelper(APICore::factory($key, $secret, $version, $storage, $signature));
	}

	public function __construct(APICore &$api) {
		$this->api = $api;
	}

	public function setUpUser($username) {
		$this->api->session->create();
		$this->api->user->create($username);
	}

	public function getProfile($username = null) {
		return $this->api->profile->retrieve($username);
	}

	public function sendToken($provider, $token, $secret = null) {
		if (empty($secret))
			return $this->api->provider->createOAuth2($provider, $token);
		return $this->api->provider->createOAuth1($provider, $token, $secret);
	}

	public function taskPolling($taskId, $interval = 500) {
		$interval = max($interval, 500);
		do {
			usleep($interval);
			$task = $this->api->task->details($taskId);
		} while ($task['running']);
		return $task;
	}

	public function tracesmartCheck(array $userdata, $check = Check::TracesmartAddress | Check::TracesmartDOB) {}

	public function tracesmartAddress() {}

	public function tracesmartDriversLicense() {}

	public function tracesmartPassport() {}

	public function tracesmartDOB() {}

	public function __get($endpoint) {
		return $this->api->$endpoint;
	}

}

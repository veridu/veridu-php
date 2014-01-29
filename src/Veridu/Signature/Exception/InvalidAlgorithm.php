<?php

namespace Veridu\Signature\Exception;

use Veridu\Exception\Exception;

class InvalidAlgorithm extends Exception {
	protected $message = 'Invalid HMAC Hash algorithm.';
}
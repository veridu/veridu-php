<?php

namespace Veridu\SDK\Exception;

use Veridu\Exception\Exception;

class InvalidResponse extends Exception {
	protected $message = 'The API response is invalid.';
}
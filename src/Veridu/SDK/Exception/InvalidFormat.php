<?php

namespace Veridu\SDK\Exception;

use Veridu\Exception\Exception;

class InvalidFormat extends Exception {
	protected $message = 'The API response is in an invalid format.';
}
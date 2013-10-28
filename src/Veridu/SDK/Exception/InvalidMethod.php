<?php

namespace Veridu\SDK\Exception;

use Veridu\Exception\Exception;

class InvalidMethod extends Exception {
	protected $message = 'Invalid fetch method.';
}
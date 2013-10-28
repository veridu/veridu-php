<?php

namespace Veridu\SDK\Exception;

use Veridu\Exception\Exception;

class InvalidUsername extends Exception {
	protected $message = 'Invalid username format.';
}
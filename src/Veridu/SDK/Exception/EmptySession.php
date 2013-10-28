<?php

namespace Veridu\SDK\Exception;

use Veridu\Exception\Exception;

class EmptySession extends Exception {
	protected $message = 'A session is required to fetch this resource.';
}
<?php

namespace MVolaphp\Objects;

use MVolaphp\Exceptions\InvalidArgumentException;

class Objects
{
	protected function checkProperty($name)
	{
		if (!\property_exists(self::class, $name))
			$this->invalidArgument("'$name' is innexistant.");
	}

	protected function invalidArgument($message)
	{
		throw new InvalidArgumentException($message);
	}
}
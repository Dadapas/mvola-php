<?php

namespace Dadapas\MobileMoney\Objects;

/**
 * This file is part of the dadapas/mvola-php library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) TOVOHERY Z. Pascal <tovoherypascal@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 */

use Dadapas\MobileMoney\Exceptions\InvalidArgumentException;

class KeyValue extends Objects
{
	protected $values = [];

	public function __get($name)
	{
		if (isset($this->values[$name]))
			return $this->values[$name];

	}

	public function addPairObject(KeyPairInterface $keypair)
	{
		$this->values[$keypair->getKey()] = $keypair->getValue();
	}

	public function add($key, $value)
	{
		if (isset($this->values[$key]))
			throw new InvalidArgumentException("$key already exist.");
		
		$this->values[$key] = $value;
	}

	public function keysExist(...$keys)
	{
		foreach( $keys as $key ) {
			if ( ! isset($this->values[$key]))
				return false;

		}
		return true;
	}

	public function __toString()
	{
		$str = "";
		$iterator = 0;
		$end = ",";

		foreach($this->values as $key => $value)
		{
			if ($iterator === count($this->values) - 1)
				$end = "";

			$str .= "{\"key\": \"$key\", \"value\": \"$value\"}". $end;

			$iterator++;
		}
		return "[$str]";
	}
}
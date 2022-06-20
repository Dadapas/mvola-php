<?php

namespace MVolaphp\Objects;

use MVolaphp\Exceptions\InvalidArgumentException;

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
		$arr = [];

		foreach($this->values as $key => $value)
		{
			$arr[] = [
				"key"	=> "$key",
				"value" => "$value"
			];
		}
		
		return json_encode($arr);
	}
}
<?php

namespace MVolaphp\Objects;


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

	public function __set($name, $value)
	{
		$this->add($name, $value);
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
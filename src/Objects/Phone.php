<?php

namespace MVolaphp\Objects;

use MVolaphp\Utils\Helpers;

class Phone extends Objects implements KeyPairInterface
{
	public $msisdn;

	public function __construct($msisdn)
	{
		if ( ! Helpers::isMadaNumber($msisdn))
			$this->invalidMsisdn();
		
		$this->msisdn = $msisdn;
	}

	protected function invalidMsisdn()
	{
		$this->invalidArgument("Invalid msisdn");
	}

	public function __set($name, $value)
	{

		if ($name == 'msisdn' && Helpers::isMadaNumber($value))
		{
			$this->{$name} = $value;
			return;
		}

		if ($name == 'msisdn')
			$this->invalidMsisdn();
	}

	public function getKey()
	{
		return 'msisdn';
	}

	public function getValue()
	{
		return $this->msisdn;
	}

	//public function 

}
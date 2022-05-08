<?php
namespace MVolaphp\Objects;

class Company implements KeyPairInterface
{
	protected $name;

	public function __construct($companyName)
	{
		$this->name = $companyName;
	}

	public function getKey()
	{
		return 'partnerName';
	}

	public function getValue()
	{
		return $this->name;
	}

	public function getName()
	{
		return $this->name;
	}
}
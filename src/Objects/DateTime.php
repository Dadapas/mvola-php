<?php

namespace MVolaphp\Objects;

class DateTime
{
	/**
	 * @property $datetime
	*/ 
	protected $datetime;


	public function __construct($strtime = "now")
	{
		$this->datetime = new \DateTime($strtime);
	}

	public function currentWithMill()
	{
		$milliseconds = \microtime(true);
    	
    	$timestamp = \floor($milliseconds);

		$uuuu = \preg_replace("/\d+\./", "", "$milliseconds");

		$u = \substr($uuuu, 0, 3);

		return \date("Y-m-d\TH:i:s", $timestamp). ".{$u}Z";
	}

	public function __toString()
	{
		$dt = $this->datetime;
		
		return $dt->format("Y-m-d\Th:i:s.v\Z"); 
	}
}
<?php

namespace MVolaphp\Objects;

use DateTime as NativeDateTime;
use function microtime;
use function floor;
use function preg_replace;
use function substr;
use function date;

class DateTime
{
	/**
	 * @property $datetime
	*/ 
	protected $datetime;


	public function __construct($strtime = "now")
	{
		$this->datetime = new NativeDateTime($strtime);
	}

	public function currentWithMill()
	{
		$milliseconds = microtime(true);
    	
    	$timestamp = floor($milliseconds);

		$uuuu = preg_replace("/\d+\./", "", "$milliseconds");

		$u = substr($uuuu, 0, 3);

		return date("Y-m-d\TH:i:s", $timestamp). ".{$u}Z";
	}

	public function __toString()
	{
		$dt = $this->datetime;
		
		return $dt->format("Y-m-d\Th:i:s.v\Z"); 
	}
}
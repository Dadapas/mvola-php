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
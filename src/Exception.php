<?php

namespace Dadapas\MobileMoney;

/**
 * This file is part of the dadapas/mvola-php library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) TOVOHERY Z. Pascal <tovoherypascal@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 */

class Exception extends \Exception
{
	protected $data = [];

	public function __construct($msg, $data=[])
	{
		parent::__construct($msg);

		$this->data = $data;
	}

	
	/**
	 * Add description of errors file
	 * 
	 * @return array $data  data about the exception
	*/ 
	public function getData()
	{
		return $this->data;
	}
}
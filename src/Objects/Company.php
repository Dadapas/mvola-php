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
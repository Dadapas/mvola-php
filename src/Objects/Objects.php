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

class Objects
{
	protected function checkProperty($name)
	{
		if (!\property_exists(self::class, $name))
			$this->invalidArgument("'$name' is innexistant.");
	}

	protected function invalidArgument($message)
	{
		throw new InvalidArgumentException($message);
	}
}
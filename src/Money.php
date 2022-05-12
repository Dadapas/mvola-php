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

class Money
{
	/**
	 * @property $devise
	*/
	protected $devise;


	/**
	 * @property $amount
	*/
	protected $amount;

	/**
	 * @property $supportedDevise
	 */ 
	protected static $supportedDevise = [
		'MGA' => 'Ar', // Malagasy Ariary

		/*'USD' => '$',  //'Dollar american'
		'EUR' => '€'   // European*/
	];

	/**
	 * @param string $devise      Money devise like USD, EUR, MGA, ...
	 * @param float $amount       The amount to send
	*/
	public function __construct($devise, $amount)
	{
		self::checkDevise($devise);

		$this->devise = $devise;
		$this->amount = $amount;
	}

	/**
	 * Get the devise
	 * @return float    Amount money
	*/
	public function getDevise()
	{
		return $this->devise;
	}

	/**
	 * Get the amount 
	 * @return float    Amount money
	*/
	public function getAmount()
	{
		return $this->amount;
	}

	public static function isSupportedDevise($devise)
	{
		return array_key_exists($devise, self::$supportedDevise);
	}

	public static function checkDevise($devise)
	{
		if ( ! self::isSupportedDevise($devise) )
			throw new Exception("Unsupported devise.");
	}

	public static function symbol($devise)
	{
		self::checkDevise($devise);

		return self::$supportedDevise[$devise];
	}
}
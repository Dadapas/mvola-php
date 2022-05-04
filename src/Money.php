<?php

namespace MobileMoney;

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
	protected static $supportedDevise = ['MGA', 'USD', 'EUR', 'JPA', 'CAD', ''];

	/**
	 * @param string $devise      Money devise like USD, EUR, MGA, ...
	 * @param float $amount       The amount to send
	*/
	public function __construct($devise, $amount)
	{
		if ( ! self::isSupportedDevise($devise) )
			throw new Exception("Devise not supported");

		$this->devise = $devise;
		$this->amount = $amount;
	}

	public function getDevise()
	{
		return $this->devise;
	}

	public function getAmount()
	{
		return $this->amount;
	}

	public static function isSupportedDevise($devise)
	{
		return array_search($devise, self::$supportedDevise);
	}
}
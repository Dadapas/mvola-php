<?php

namespace MobileMoney;

/**
 * Mvola Class
 * Handle Mvola http request 
 * 
*/
class Telma implements ISender
{
	const BASE_URL = "https://develop.mvola.com/portal";

	const STATUS_PENDING = 0;

	const STATUS_SUCCESS = 1;

	const STATUS_FAILED  = -1;
	
	/**
	 * @property $curl
	*/
	protected $curl;

	/**
	 * @property $token
	*/
	protected $token;


	public function __construct(array $options = [])
	{
		$this->curl = curl_init(self::BASE_URL);

	}

	/**
	 * Build url 
	*/


	/**
	 * @param $amount    Amount to send in ariary
	 * @param $to        Telephone number
	 * 
	 * @return array     response to the server
	*/
	public function sendMoney(Money $amount, $to)
	{
		// TODO: fix the amount low or toheigth

		// TODO: verify number if its not correct or not (03XXXXXXX or +261(0)3XXXXXXXXX)

		echo "Money sended to {$to}".PHP_EOL;
	}
}
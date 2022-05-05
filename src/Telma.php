<?php

namespace MobileMoney;

use MobileMoney\Cache\Cache;

/**
 * Mvola Class
 * Handle Mvola http request 
 * 
*/
class Telma implements ISender
{
	const SANDBOX_URL = "https://devapi.mvola.mg";

	const PRODUCTION_URL = "https://api.mvola.mg";

	const VERSION  = "1.0.0";

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

	/**
	 * @property $cache
	*/
	protected $cache;

	/**
	 * @property $env
	*/
	protected $env = 'development';

	/**
	 * @property $headers
	*/ 
	protected $headers = [];

	/**
	 * The constructor
	 * 
	 * @param $options  Options array
	 * @param $cache    The cache path
	*/ 
	public function __construct($options = [], $cache)
	{

		if ( ! ( array_key_exists('client_id', $options) &&
				 array_key_exists('client_secret', $options)
			   )
		)
			throw new Exception('Customer client id and secret is required.');

		$this->client_id = $options['client_id'];

		$this->client_secret = $options['client_secret'];

		if (
			isset($options['isProduction']) &&
			$options['isProduction']
		)
			$this->env = 'production';

		// TODO: get the token and expires
		// TODO: pass to the cache param
		
		$this->curl = curl_init();

		Cache::setPath($cache);

		\curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);

		$this->token = new Token($this->client_id, $this->client_secret, $this->getBaseUrl().'/token');
	}

	/**
	 * Set env to prod
	*/
	public function setProduction()
	{
		$this->env = 'production';
	}

	protected function getBaseUrl()
	{
		return $this->env == 'production' ? self::PRODUCTION_URL : self::SANDBOX_URL;
	}

	public function merchentUrl()
	{
		return $this->getBaseUrl(). "/mvola/mm/transactions/type/merchantpay/".self::VERSION;
	}

	protected function setUpToken()
	{
		$token = $this->token->get();

		$this->headers['Authorization'] = 'Bearer '.$token;
		$this->headers['Content-Type'] = 'application/json; charset=utf-8';
	}

	protected function setUpUri($uri)
	{
		$url = $this->merchentUrl();

		$this->setOption(CURLOPT_URL, $url.$uri);
	}

	protected function run()
	{
		$this->setUpToken();
		
		$output = \curl_exec($this->curl);

		if ($output !=  null)
			return \json_decode($output, true);

		return [];
	}

	protected function setOption($name, $value)
	{
		\curl_setopt($this->curl, $name, $value);
	}

	public function setCallbackUrl($url)
	{
		$this->headers['X-Callback-URL'] = $url;
	}

	protected function setUpHeaders()
	{

		$this->headers['X-CorrelationID'] = "X-CorrelationID";
		$this->headers['UserAccountIdentifier'] = $this->client_id;
		$this->headers['Cache-Control'] = "no-cache";

		$headers = [];
		foreach($this->headers as $key => $value)
		{
			$headers[] = "{$key}: {$value}";
		}

		$this->setOption(CURLOPT_HTTPHEADER, $headers);
	}

	/**
	 * Get one transaction by ref
	 * @return array
	*/ 
	public function transaction($transID)
	{
		$this->headers['Version'] = '1.0';

		$this->setUpUri("/$transID");

		return $this->run();
	}

	/**
	 * Get status by correllation
	*/
	public function status($correlationID)
	{
		$this->headers['Version'] = self::VERSION;
		
		$this->headers['partnerName'] = "partname";

		$this->setUpUri("/status/{$correlationID}");

		return $this->run();
	}



	/**
	 * @param integer|float $amount    Amount to send in ariary
	 * @param string $to        Telephone number
	 * 
	 * @return array     response to the server
	*/
	public function sendMoney(Money $amount, $to)
	{
		// Setup return url
		

		$postData = [
			'amount'			=> "{$amount->getAmount()}",
			'currency'			=> "{$amount->getDevise()}",
			'descriptionText'	=> "Test description",
			'requestingOrganisationTransactionReference'=> uniqid('ref_'),
			'requestDate'       => date('Y-m-d'),
			'originalTransactionReference' => 'ref_6548765',
			'debitParty'	=> [
				[
					'key'	=> 'msisdn',
					'value'	=> '0.0'
				]
			],
			'creditParty'	=> [
				[
					'key'	=> 'msisdin',
					'value'	=> $to
				]
			],
			'metadata'		=> [
				[
					'key'	=> 'fr',
					'value'	=> ""
				]
			]
		];

		$this->setUpUri("/");
		
		$this->setOption(CURLOPT_CUSTOMREQUEST, "POST");

		$this->setOption(CURLOPT_POSTFIELDS, \http_build_query($postData));

		$this->setOption(CURLOPT_MAXREDIRS, 10);

		$this->setOption(CURLOPT_TIMEOUT, 30);

		$this->setOption(CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

		return $this->run();
	}

	/**
	 * @param integer|float $amount    Amount to send in ariary
	 * @param string $to        Telephone number
	 * 
	 * @return array     response to the server
	*/
	public function send(Money $amount, $to)
	{
		return $this->sendMoney($amount, $to);
	}


	public function __descruct()
	{
		\curl_close($this->curl);
	}
}
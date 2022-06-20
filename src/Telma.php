<?php

namespace MVolaphp;

use MVolaphp\Cache\Cache;
use MVolaphp\Exceptions\{InvalidArgumentException, HttpRequestException};
use MVolaphp\Objects\{PayIn, Phone, KeyValue};
use MVolaphp\Utils\Helpers;

/**
 * Mvola Class
 * Handle Mvola http request 
 * 
*/
class Telma implements IPay
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
	 * @property $merchant_number
	*/
	protected $merchant_number;

	/**
	 * @property $partner_name
	*/
	protected $partner_name; 

	/**
	 * The constructor
	 * 
	 * @param $options  Options array
	 * @param $cache    The cache path
	*/ 
	public function __construct($options = [], $cache)
	{

		if ( ! ( array_key_exists('client_id', $options) &&
				 array_key_exists('client_secret', $options) &&
				 array_key_exists('merchant_number', $options) &&
				 array_key_exists('partner_name', $options)
			   )
		)
			throw new InvalidArgumentException('Customer client_id, client_secret, merchant_number and partner_name are required.');

		$this->client_id = $options['client_id'];

		$this->client_secret = $options['client_secret'];
		
		$this->merchant_number = new Phone($options['merchant_number']);
		
		$this->partner_name = $options['partner_name'];

		if ( array_key_exists('lang', $options))
		{
			$this->lang = $options['lang'];
		} else {
			$this->lang = "FR";
		}

		if (
			isset($options['production']) &&
			$options['production']
		)
			$this->env = 'production';
		
		$this->initRequest();

		Cache::setPath($cache);

		$this->token = new Token($this->client_id, $this->client_secret, $this->getBaseUrl().'/token');
		
	}

	public function initRequest()
	{
		$this->curl = \curl_init();

		\curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
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

	protected function setUpUri($uri)
	{
		$url = $this->merchentUrl();
		if ("/" === $uri)
			$uri = "";

		$this->setOption(CURLOPT_URL, $url.$uri);
	}

	protected function run()
	{

		$result = curl_exec($this->curl);

		if (curl_error($this->curl))
		{
			$data = [
				'error'	=> 'curl error',
				'error_description'	=> curl_error($this->curl)
			];
			throw new HttpRequestException('request error', $data);
		}

		$code = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);

		$_url = curl_getinfo($this->curl, CURLINFO_EFFECTIVE_URL);

		curl_close($this->curl);


		$dataResponse = json_decode($result, true);

		if ($code >= 300)
		{
			$dataResponse['effective_url'] = $_url;
			throw new HttpRequestException("Server request error.", $dataResponse);
		}

		if ($dataResponse == null) return [];

		return $dataResponse;
	}

	protected function setOption($name, $value)
	{
		\curl_setopt($this->curl, $name, $value);
	}

	public function setCallbackUrl($url)
	{
		if ( ! Helpers::isUrl($url) )
		{
			$data = [
				'error'	=> 'bad url',
				"error_description"	=> "$url is not valid url."
			];
			throw new InvalidArgumentException("invalid url.", $data);
		}

		$this->headers['X-Callback-URL'] = $url;
	}

	public function setUpLangue($lang = "MG")
	{
		if ($lang == "MG" || $lang == "FR") {
			$this->lang = $lang;
			return;
		}
	}

	protected function setUpHeaders()
	{
		$token = $this->token->get();

		$this->headers['Accept'] = "application/json";
		$this->headers['Authorization'] = 'Bearer '.$token;
		$this->headers['Content-Type'] = 'application/json';
		$this->headers['UserLanguage'] = $this->lang;

		$this->headers['Version'] = "1.0";
		$this->headers['X-CorrelationID'] = Helpers::uuid();
		$this->headers['UserAccountIdentifier'] = "msisdn;". $this->merchant_number->getValue();
		$this->headers['partnerName'] = $this->partner_name;
		$this->headers['Cache-Control'] = "no-cache";
		$this->headers['Strict-Transport-Security'] = "max-age=31536000; includeSubDomains; preload";
		$this->headers['X-XSS-Protection'] = "0";
		$this->headers['X-Content-Type-Options'] = "nosniff";
		$this->headers['X-Frame-Options'] = "sameorigin";
		$this->headers['Referrer-Policy'] = "same-origin";


		$headers = [];
		foreach($this->headers as $key => $value)
		{
			$headers[] = "{$key}: {$value}";
		}

		$this->setOption(CURLOPT_HTTPHEADER, $headers);
	}

	/**
	 * Sending money to merchent
	 * 
	 * @param PayIn $payment      Details of payment
	 * 
	 * @return array     response to the server
	*/
	public function payIn(PayIn $payment)
	{
		// Check payement details

		$payment->checkRequiredProperty();

		$amount = $payment->amount;
		
		$credited = $payment->creditParty;

		if ($credited == null)
		{
			$credited = new KeyValue();
			$credited->addPairObject($this->merchant_number);
			$payment->creditParty = $credited;
		}

		$payment->checkPropertyValues();

		$lavabe = $payment->requestingOrganisationTransactionReference;

		$symbol = Money::symbol($amount->getDevise());


		$postData = [
			'amount'			=> "{$amount->getAmount()}",
			'currency'			=> "{$symbol}",
			'descriptionText'	=> $payment->descriptionText,
			'requestingOrganisationTransactionReference'=> $lavabe,
			'requestDate'       =>  $payment->requestDate,
			'originalTransactionReference' => $payment->originalTransactionReference,
			'creditParty'	=> (string) $credited,
			'debitParty'	=> (string) $payment->debitParty,
			'metadata'		=> (string) $payment->metadata
		];

		$encodeData = json_encode($postData);

		$this->initRequest();

		$this->setUpUri("/");
		
		$this->setOption(CURLOPT_POST, 1);

		$this->headers['Content-Length'] = strlen($encodeData);

		$this->setOption(CURLOPT_POSTFIELDS, $encodeData);

		$this->setUpHeaders();

		$this->setOption(CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

		return $this->run();
	}

	/**
	 * Get status by correllation
	*/
	public function status($correlationID)
	{
		$this->initRequest();

		$this->setUpUri("/status/{$correlationID}");

		$this->setUpHeaders();

		return $this->run();
	}

	/**
	 * Get one transaction by ref
	 * @return array
	*/ 
	public function transaction($transID)
	{
		$this->initRequest();

		$this->setUpUri("/{$transID}");

		$this->setUpHeaders();

		return $this->run();
	}


	public function __descruct()
	{
		curl_close($this->curl);
	}
}
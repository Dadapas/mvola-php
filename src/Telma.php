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
				 array_key_exists('merchant_number', $options)
			   )
		)
			throw new InvalidArgumentException('Customer client_id, client_secret and merchant_number are required.');

		$this->client_id = $options['client_id'];

		$this->client_secret = $options['client_secret'];
		
		$this->merchant_number = new Phone($options['merchant_number']);

		if (array_key_exists('partner_name', $options))
		{
			$this->partner_name = $options['partner_name'];
		}

		if (
			isset($options['production']) &&
			$options['production']
		)
			$this->env = 'production';

		// TODO: get the token and expires
		// TODO: pass to the cache param
		
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

		$this->setOption(CURLOPT_URL, $url.$uri);
	}

	protected function run()
	{

		$output = \curl_exec($this->curl);

		if (\curl_error($this->curl))
			throw new HttpRequestException(\curl_error($this->curl));

		\curl_close($this->curl);

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

	public function setUpLangue($lang = "MG")
	{
		if ($lang == "MG" || $lang == "FR") {
			$this->headers['UserLanguage']  = $lang;
			return;
		}

		$this->headers['UserLanguage'] = "MG";
	}

	protected function setUpHeaders()
	{
		$token = $this->token->get();

		$this->headers['Authorization'] = 'Bearer '.$token;
		$this->headers['Content-Type'] = 'application/json';

		$this->headers['Version'] = "1.0";
		$this->headers['X-CorrelationID'] = Helpers::uuid();
		$this->headers['UserAccountIdentifier'] = "msisdn;". $this->merchant_number->getValue();
		$this->headers['partnerName'] = $this->partner_name;
		$this->headers['Cache-Control'] = "no-cache";

		$headers = [];
		foreach($this->headers as $key => $value)
		{
			$headers[] = "{$key}: {$value}";
		}
		$this->setOption(CURLINFO_HEADER_OUT, true);
		$this->setOption(CURLOPT_HTTPHEADER, $headers);
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

		if ($payment->metadata->partnerName)
			$this->headers['partnerName'] = $payment->metadata->partnerName;


		$symbol = Money::symbol($amount->getDevise());


		$postData = [
			'amount'			=> "{$amount->getAmount()}",
			'currency'			=> "{$symbol}",
			'descriptionText'	=> $payment->descriptionText,
			'requestingOrganisationTransactionReference'=> $lavabe,
			'requestDate'       =>  $payment->requestDate,
			'originalTransactionReference' => $payment->originalTransactionReference,
			'debitParty'	=> (string) $payment->debitParty,
			'creditParty'	=> (string) $credited,
			'metadata'		=> (string) $payment->metadata
		];

		$this->initRequest();

		$this->setUpUri("/");
		
		$this->setOption(CURLOPT_CUSTOMREQUEST, "POST");

		$this->setOption(CURLOPT_POSTFIELDS, \http_build_query($postData));

		$this->setOption(CURLOPT_MAXREDIRS, 10);

		$this->setOption(CURLOPT_TIMEOUT, 30);

		$this->setOption(CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

		$this->setUpHeaders();

		return $this->run();
	}


	public function __descruct()
	{
		\curl_close($this->curl);
	}
}
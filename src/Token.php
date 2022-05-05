<?php

namespace MobileMoney;

use MobileMoney\Cache\Cache;

class Token
{
	/**
	 * @property $curl
	*/
	protected $curl;

	/**
	 * @property $url
	*/
	protected $url;

	/**
	 * @property $client_id
	*/
	protected $client_id;

	/**
	 * @property $client_secret
	*/
	protected $client_secret;

	/**
	 * @propery $cache
	*/
	protected $cache; 


	public function __construct($client_id, $client_secret, $url)
	{
		$this->curl = curl_init();
		$this->url  = $url;
		$this->client_id = $client_id;
		$this->client_secret = $client_secret;

		$this->cache = new Cache();

		\curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
	}

	protected function getBasicAuthentificationHeader($contentType='application/json')
	{
		$username = $this->client_id;
		$password = $this->client_secret;
		return array(
			'Content-Type: '.$contentType,
			'Cache-Control: no-cache',
			'Authorization: Basic '. \base64_encode("$username:$password")
    	);
	}

	private function getAccessToken()
	{
		$url = $this->getBaseUrl();

		$post_fields = \http_build_query([
			'grant_type'	=> 'client_credentials',
			'scope'			=> 'EXT_INT_MVOLA_SCOPE'
		]);

		\curl_setopt($this->curl, CURLOPT_URL, $this->url);
		$headers = $this->getBasicAuthentificationHeader('application/x-www-form-urlencoded');
		
		\curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);
		\curl_setopt($this->curl, CURLOPT_POST, 1);
		\curl_setopt($this->curl, CURLOPT_POSTFIELDS, $post_fields);

		$result = \curl_exec($this->curl);

		return \json_decode($result, true);
  	}

	public function get()
	{
		$content = $this->cache->read();

		if (isset($content['access_token']))

		$token = $this->getAccessToken();

	}
}
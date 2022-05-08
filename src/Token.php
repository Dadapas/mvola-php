<?php

namespace MVolaphp;

use MVolaphp\Cache\Cache;
use MVolaphp\Objects\Token as TokenObject;
use MVolaphp\Exceptions\HttpRequestException;
use MVolaphp\Utils\Helpers;

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
		$url = $this->url;

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

		if ( \curl_errno($this->curl))
		{
			$data = [
				'error' => 'unable to reach domain.',
				'error_description'	=> \curl_error($this->curl)
			];
			throw new HttpRequestException("unable to reach domain.", $data);
		}

		$code = \curl_getinfo($this->curl, CURLINFO_HTTP_CODE);

		\curl_close($this->curl);

		$tokenResponse = \json_decode($result, true);

		if ($code > 200)
		{
			throw new HttpRequestException("request server error.", $tokenResponse);
		}

		if ($tokenResponse == null) return [];

		return $tokenResponse;
  	}

  	protected function cached(TokenObject $tokenObject = null)
  	{
  		if ($tokenObject == null)
  			$tokenObject = new TokenObject();

  		$tokenArray = $this->getAccessToken();

  		if( ! array_key_exists('access_token', $tokenArray) )
  			throw new Exception("Unable to get token.");

		$token =  $tokenArray['access_token'];
		$tokenObject->access_token = $token;
		
		$date = new \DateTime();

		$secondes = $tokenArray['expires_in'];

		$date->add(new \DateInterval("PT{$secondes}S"));

		$tokenObject->expires_in = $date->getTimestamp();

		$this->cache->write(serialize($tokenObject));

		return $token;
  	}

  	/**
  	 *  Get access token string
  	 * @return string
  	*/
	public function get()
	{
		$content = $this->cache->read();
		$token = "";
		
		$tokenObject = null;

		if (1 === preg_match('/C\:\d+\:\".+access_token.+expires_in.+/', $content))
		{
			$tokenObject = unserialize($content);

			// is token expired
			if ($tokenObject->expires_in > time())
			{
				return $tokenObject->access_token;
			}
		}

		return $this->cached($tokenObject);

	}

	protected function acessForTest()
  	{
  		return [
			"access_token" => \MVolaphp\Utils\Helpers::correlationID(),
			"scope"		   => "EXT_INT_MVOLA_SCOPE",
			"token_type"   => "Bearer",
			"expires_in"   => 10 // Alive for 10 seconds
		];
  	}

	protected function cachedTest(TokenObject $tokenObject = null)
  	{
  		if ($tokenObject == null)
  			$tokenObject = new TokenObject();

  		$tokenArray = $this->acessForTest();

  		if( ! array_key_exists('access_token', $tokenArray) )
  			throw new Exception("Unable to get token.");

		$token =  $tokenArray['access_token'];
		$tokenObject->access_token = $token;
		
		$date = new \DateTime();

		$secondes = $tokenArray['expires_in'];

		$date->add(new \DateInterval("PT{$secondes}S"));

		$tokenObject->expires_in = $date->getTimestamp();

		$this->cache->write(serialize($tokenObject));

		return $token;
  	}

	public function getTest()
	{
		$content = $this->cache->read();
		$token = "";
		
		$tokenObject = null;

		if (1 === preg_match('/C\:\d+\:\".+access_token.+expires_in.+/', $content))
		{
			$tokenObject = unserialize($content);

			// is token expired
			if ($tokenObject->expires_in > time())
			{
				return $tokenObject->access_token;
			}
		}

		return $this->cachedTest($tokenObject);
	}

	public function __descruct()
	{
		\curl_close($this->curl);
	}
}
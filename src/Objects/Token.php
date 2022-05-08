<?php

namespace MVolaphp\Objects;

use Serializable;

class Token extends Objects implements Serializable
{
	public $access_token;

	public $expires_in;

	public function serialize()
	{
		return serialize([
			'access_token'	=> $this->access_token,
			'expires_in'	=> $this->expires_in
		]);
	}

	public function unserialize($data)
	{
		$data = unserialize($data);
		$this->access_token = $data['access_token'];
		$this->expires_in = $data['expires_in'];
	}
}
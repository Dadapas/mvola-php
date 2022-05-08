<?php

namespace MVolaphp;

class Exception extends \Exception
{
	protected $data = [];

	public function __construct($msg, $data=[])
	{
		parent::__construct($msg);

		$this->data = $data;
	}

	
	/**
	 * Add description of errors file
	 * 
	 * @return array $data  data about the exception
	*/ 
	public function getData()
	{
		return $this->data;
	}
}
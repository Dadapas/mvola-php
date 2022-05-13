<?php

namespace MVolaphp\Objects;

use MVolaphp\Utils\Helpers;
use MVolaphp\Money;
use MVolaphp\Exceptions\InvalidArgumentException;
use MVolaphp\Objects\{Phone, KeyValue, DateTime};

/**
 * PayIn Details payement
 * 
*/ 
class PayIn extends Objects
{
	public $amount;

	public $descriptionText;

	public $originalTransactionReference;

	public $requestDate; // Format yyyy-MM-ddTHH:mm:ss.SSSZ

	public $debitParty;

	public $requestingOrganisationTransactionReference;

	public $creditParty;

	public $metadata;

	public function __construct()
	{
		$this->requestingOrganisationTransactionReference = Helpers::transRef();
		$this->originalTransactionReference = Helpers::ref();
		
		$this->requestDate = (new DateTime)->currentWithMill();
	}

	public function __set($name, $value)
	{
		$this->checkProperty($name);

		$this->{$name} = $value;
	}

	public function checkRequiredProperty()
	{
		if (
			$this->amount == null ||
			$this->originalTransactionReference == null ||
			$this->descriptionText == null ||
			$this->requestDate == null ||
			$this->debitParty == null ||
			$this->requestingOrganisationTransactionReference == null ||
			$this->metadata == null
		)
			throw new InvalidArgumentException("Some required parameters are missing.", [
				'error'	=> 'invalid arguments',
				'error_description'	=> [
					'message'	=> 'The list of arguments elements are not allowed to be null.',
					'args'		=> 'amount, descriptionText, debitParty, metadata, originalTransactionReference, requestingOrganisationTransactionReference, requestDate'
				]
			]);
	}

	/**
	 * Check all property value and remove extra string .,-_
	 * 
	 * @return void
	*/ 
	public function checkPropertyValues()
	{
		if ( !($this->amount instanceof Money))
			throw new InvalidArgumentException("Amount must be money");

		if ( !($this->metadata instanceof KeyValue && $this->metadata->keysExist('partnerName') ) )
			throw new InvalidArgumentException("metadata must be keyvalue and partnerName key as company name");

		if ( !($this->debitParty instanceof KeyValue && $this->debitParty->keysExist('msisdn')) )
			throw new InvalidArgumentException("debitParty must be keyvalue and have msisdn");

		if ( !($this->creditParty instanceof KeyValue && $this->creditParty->keysExist('msisdn')) )
			throw new InvalidArgumentException("creditParty must be keyvalue and have msisdn");

		// Date format
		if ( !(1 === preg_match('/\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.\d{3}Z/', $this->requestDate)) )
			throw new InvalidArgumentException("requestDate format yyyy-MM-ddTHH:mm:ss.SSSZ");

		// 
		$lavabe = $this->requestingOrganisationTransactionReference;
		$lavabe = Helpers::removeExtraChar($lavabe);
		$this->requestingOrganisationTransactionReference = $lavabe;

		if ( !(strlen($lavabe) >= 10 || strlen($lavabe) <= 50 || $lavabe != "") )
			throw new InvalidArgumentException("length of requestingOrganisationTransactionReference must under 50 and less than 10 and not be empty");

		// Automatictly removed
		$this->descriptionText = Helpers::removeExtraChar($this->descriptionText);

		if ($this->descriptionText == "")
			$this->descriptionText = "Description of test";


		if ( !(strlen($this->originalTransactionReference) >= 7) )
			throw new InvalidArgumentException("originalTransactionReference length 7");
	}
}
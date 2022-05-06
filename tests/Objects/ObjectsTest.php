<?php declare(strict_types=1);

namespace MobileMoney\Tests\Cache;

use PHPUnit\Framework\TestCase;
use MobileMoney\Utils\Helpers;
use MobileMoney\Objects\{Phone, Token, PayIn, KeyValue};
use MobileMoney\Money;

final class ObjectsTest extends TestCase
{
    public function testPhone()
    {
    	$phone = new Phone("0336549854");

    	$phone->msisdn = '0386645656';


    	$this->assertTrue(true);
    }

    public function testPayIn()
    {
    	$payin = new PayIn();

    	$payin->amount = new Money('MGA', 5000);

    	$payin->descriptionText = "Un description de bla bla";

    	$credit = new KeyValue();

    	$merchent = new Phone("0342516025");
    	
    	$credit->addPairObject($merchent);

    	$payin->creditParty = $credit;

    	$this->assertTrue(true);
    }

    public function testIsCorrectInstance()
    {
        $payIn = new PayIn();
        
        $amount = new Money('MGA', 1000);

        $meta = new KeyValue();
        
        $meta->add('fc', 'USD');
        $meta->add('amountFc', "1");
        $meta->add('partnerName', "company");

        $payIn->metadata = $meta;

        $debit = new KeyValue();
        $debit->add('msisdn', "fzefzeee");

        $payIn->debitParty = $debit;

        $payIn->creditParty = new KeyValue();
        $payIn->requestDate = date('c');
        $payIn->requestDate = "2021-02-24T03:28:00.567Z";
        //$payIn->

        $payIn->amount = $amount;

        $payIn->checkPropertyValue();

        $this->assertTrue(true);
    }
}
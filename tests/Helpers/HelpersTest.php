<?php declare(strict_types=1);

namespace MVolaphp\Tests\Cache;

use PHPUnit\Framework\TestCase;

use MVolaphp\Utils\Helpers;

final class HelpersTest extends TestCase
{
    public function testNumero()
    {
    	// This exist
    	$this->assertTrue(Helpers::isMadaNumber("0342516025"), "Right numero");
    	
    	$this->assertFalse(Helpers::isMadaNumber("03465654895", "Midepasse zero ray"));

    	// Not exist
    	$this->assertFalse(Helpers::isMadaNumber("0366565489", "Wrong numero"));


    }

    public function testReferences()
    {
    	$str = Helpers::ref();

    	$this->assertStringMatchesFormat('%d', $str, "String format");
    }

    public function testCorrelationID()
    {
    	$this->assertStringContainsString("-", Helpers::correlationID(), "Separated by -");

    	$this->assertNotSame(Helpers::correlationID(), Helpers::correlationID(), "Always generated new one.");

    }

    public function testTransactionRef()
    {
        $transRef = Helpers::transRef();

        $this->assertFalse(1 === preg_match("/(\-|\_|\.|\,)/", $transRef), "Should has no - _ . , in it");
    }

    public function testUuid()
    {
        $uuid = Helpers::uuid();

        // The uuid is less than 40
        $this->assertLessThanOrEqual(40, strlen($uuid), "Should less than 40");
    }
}
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

        preg_match_all('/\-/', $uuid, $matches);

        $this->assertSame(4, count($matches[0]), "Must have 4 -(s).");
        // The uuid is less than 40
        $this->assertLessThanOrEqual(40, strlen($uuid), "Should less than 40");
    }

    public function testUrl()
    {
        $rightUrl = [
            "http://www.foufos.gr",
            "https://www.foufos.gr",
            "http://foufos.gr",
            "http://www.foufos.gr/kino",
            "http://werer.gr",
            "www.foufos.gr",
            "www.mp3.com",
            "www.t.co",
            "http://t.co",
            "http://www.t.co",
            "https://www.t.co",
            "www.aa.com",
            "http://aa.com",
            "http://www.aa.com",
            "https://www.aa.com",
        ];

        $wrongUrl = [
            "www.foufos",
            "www.foufos-.gr",
            "www.-foufos.gr",
            "foufos.gr",
            "http://www.foufos",
            "http://foufos",
            "www.mp3#.com",
        ];

        foreach($rightUrl as $url)
        {
            $this->assertTrue(Helpers::isUrl($url), "right url");
        }

        foreach($wrongUrl as $url)
        {
            $this->assertFalse(Helpers::isUrl($url), "wrong url");
        }
    }
}
<?php declare(strict_types=1);

namespace MVolaphp\Tests\Auth;

use PHPUnit\Framework\TestCase;
use MVolaphp\{Token, Telma as MVola};
use MVolaphp\Objects\Token as TokeObject;
use MVolaphp\Cache\Cache;

final class TokenTest extends TestCase
{
	public function testValidity()
	{
		$cacheDir = dirname(__DIR__, 2)."/cache";
		Cache::setPath($cacheDir);

		$customerId = "customer_id";
		$customerSecret = "customer_secret";
		$tokenUrl = "https://";
		
		try {
			$token = new Token($customerId, $customerSecret, $tokenUrl);
		}
		catch (HttpRequestException $e) {

		}

		$access = $token->getTest();
		
		$another = $token->getTest();

		$this->assertSame( $access , $another , "token not expired yet");

		//$this->assertTrue(true);
	}
}
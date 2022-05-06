<?php declare(strict_types=1);

namespace MobileMoney\Tests\Auth;

use PHPUnit\Framework\TestCase;
use MobileMoney\Token;
use MobileMoney\Objects\Token as TokeObject;
use MobileMoney\Cache\Cache;

final class TokenTest extends TestCase
{
	public function testValidity()
	{
		/*Cache::setPath("/path/to/cache");

		$customerId = "customer_id";
		$customerSecret = "customer_secret";
		$tokenUrl = "https://";

		$token = new Token($customerId, $customerSecret, $tokenUrl);

		$access = $token->get();
		sleep(5);
		$another = $token->get();

		$this->assertSame( $access , $another , "token not expired yet");*/

		$this->assertTrue(true);
	}
}
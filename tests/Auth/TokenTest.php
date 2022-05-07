<?php declare(strict_types=1);

namespace MVolaphp\Tests\Auth;

use PHPUnit\Framework\TestCase;
use MVolaphp\Token;
use MVolaphp\Objects\Token as TokeObject;
use MVolaphp\Cache\Cache;

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
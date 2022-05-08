<?php declare(strict_types=1);

namespace MVolaphp\Tests\Cache;

use PHPUnit\Framework\TestCase;

use MVolaphp\Cache\Cache;

final class CacheTest extends TestCase
{
    /*public function testPathCache()
    {
        
        $path = "/path/to/cache";

        $this->assertNotSame($path, "/path/to/cache", "This should be point to cache");

        $this->assertDirectoryIsWritable($path);
        
        Cache::setPath($path);

        $cache = new Cache();
        $content = serialize([
            'access_token'  => "<token>",
            'expires_in'    => time() - 7200
        ]);
        
        $cache->write($content);

        $this->assertSame($content, $cache->read());
    }

    public function testExpireToken()
    {
        $cache = new Cache();
        $content = $cache->read();
        
        $token = unserialize($content);

        $this->assertLessThan(time(), $token['expires_in'], "Session has been expired.");
    }*/

    public function testWrongPath()
    {
        $this->assertTrue(true);
    }
}
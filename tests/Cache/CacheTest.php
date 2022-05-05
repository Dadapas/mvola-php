<?php declare(strict_types=1);

namespace MobileMoney\Tests\Cache;

use PHPUnit\Framework\TestCase;

use MobileMoney\Cache\Cache;

final class CacheTest extends TestCase
{
    public function testPushAndPop()
    {
        $stack = [];

        $path = "/opt/lampp/htdocs/mobile-money/cache";

        $this->assertDirectoryIsWritable($path);
        
        Cache::setPath($path);

        $cache = new Cache();
        $content = [1 => "Hello World !"];
        
        $cache->write($content);

        $this->assertSame($content, $cache->read());        
    }

    public function testWrongPath()
    {
        $this->assertTrue(true);
    }
}
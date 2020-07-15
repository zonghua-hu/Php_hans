<?php


namespace ObjectPool;

use PHPUnit\Framework\TestCase;

/**
 * 对象池模式测试用例
 * Class PoolTest
 * @package ObjectPool
 */
class PoolTest extends TestCase
{
    public function testCanGetNewInstancesWithGet()
    {
        $pool = new WorkPool();
        $workerOne = $pool->get();
        $workerTwo = $pool->get();

        $this->assertCount(2, $pool);

        $this->assertNotSame($workerOne, $workerTwo);
    }

    public function testCanGetSameInstanceTwiceWhenDisposingItFirst()
    {
        $pool = new WorkPool();
        $workerOne = $pool->get();

        $pool->dispose($workerOne);

        $workerTwo = $pool->get();

        $this->assertCount(1, $pool);
        $this->assertSame($workerTwo, $workerOne);
    }
}

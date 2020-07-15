<?php


namespace SimpleFactory;

use PHPUnit\Framework\TestCase;

/**
 * 简单工厂.测试用例
 * Class SimpleFactoryTest
 * @package SimpleFactory
 */
class SimpleFactoryTest extends TestCase
{
    public function testCanCreateBicycle()
    {
        $bicycle = (new SimpleFactory())->createBicycle();
        $this->assertInstanceOf(Bicycle::class, $bicycle);
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/7/9
 * Time: 17:25
 */
use phpunit\Framework\TestCase;


class StackTestClass extends  TestCase
{
    public function testOne()
    {
        $ars = [1,2,3];
        $this->assertEquals(3,count($ars));
    }
    /**
     * @depends testOne
     */
    public function testPushAndPop()
    {
        $stack = [];
        $this->assertEquals(0, count($stack));
        array_push($stack, 'foo');
        $this->assertEquals(1, count($stack));
        $this->assertEquals('foo', array_pop($stack));
        $this->assertEquals(0, count($stack));
    }

    /**
     * @depends testPushAndPop
     */
    public function testSucceeds()
    {
        $str = 'anbshdkxma';
        $this->assertEquals(10,strlen($str));
    }

}
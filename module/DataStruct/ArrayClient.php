<?php


namespace module\DataStruct;

use PHPUnit\Framework\TestCase;

class ArrayClient extends TestCase
{
    public function testRun()
    {
        $arrObj = new ArrayStack();
        $arrObj->push(2);
        $arrObj->push(3);
        $arrObj->push(4);
        $arrObj->push(5);
        $arrObj->push(6);
        $arrObj->push(7);
        $arrObj->push(8);
        $arrObj->push(9);
        $count = $arrObj->countStack();

        $this->assertCount($count, 8);
        var_dump($arrObj->pop());
    }
}

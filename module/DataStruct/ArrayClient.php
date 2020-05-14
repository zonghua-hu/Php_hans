<?php


namespace module\DataStruct;


class ArrayClient
{
    public function run()
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
        var_dump($arrObj->pop());
    }

}
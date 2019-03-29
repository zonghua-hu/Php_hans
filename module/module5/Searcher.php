<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/28
 * Time: 14:57
 */

class Searcher extends AbstractSearch
{
    private $object = null;

    public function __construct($obj)
    {
        if ($obj instanceof GirlAgain) {
            $this->object = $obj;
        }
    }

    public function show()
    {
        $this->object->greatTemp();
        $this->object->lookNice();
        $this->object->goodBody();
    }

}
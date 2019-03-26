<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/26
 * Time: 16:19
 */

class Driver implements IDrive
{
    private $obj;

    public function __construct($obj)
    {
        $this->obj = $obj;
        if ($obj instanceof Benx) {
            return $this->obj;
        } elseif ($obj instanceof BWM) {
            return $this->obj;
        } else {
            $this->obj = null;
        }
    }

    public function drive()
    {
        return $this->obj->carRun();
    }
}
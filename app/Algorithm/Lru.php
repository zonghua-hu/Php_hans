<?php

namespace app\Algorithm;

class Lru
{
    private $lruList = array();
    private $lruLength = 0;

    public function __construct($size)
    {
        $this->lruLength = $size;
    }

    public function setValue($key, $value)
    {
        if (array_key_exists($key, $this->lruList)) {
            unset($this->lruList[$key]);
        }
        if (count($this->lruList) > $this->lruLength) {
            array_shift($this->lruList);
        }
        $this->lruList[$key] = $value;
    }

    public function getValue($key)
    {
        $retValue = false;
        if (array_key_exists($key, $this->lruList)) {
            $retValue = $this->lruList[$key];
            unset($this->lruList[$key]);
            $this->lruList[$key] = $retValue;
        }
        return $retValue;
    }

    public function echoCache()
    {
        print_r($this->lruList);
    }
}

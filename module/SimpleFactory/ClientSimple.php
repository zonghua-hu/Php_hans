<?php


namespace SimpleFactory;

class ClientSimple
{
    private $address = '｜仙阕皇城｜';

    public function main()
    {
        $factory = new SimpleFactory();
        $bicycle = $factory->createBicycle();
        $bicycle->driveTo($this->address);
    }
}

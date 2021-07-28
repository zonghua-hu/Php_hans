<?php


class FacadeClient
{
    public function run()
    {
        $facade = new Facade(new BiosSony(), new OsSony());
        $a = 10;
        do {
            $facade->turnOn();
            $a --;
        } while ($a == 0);
        $facade->turnOff();
    }
}

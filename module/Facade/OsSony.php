<?php


class OsSony implements OsInterface
{
    public function getName() : string
    {
        return "SONY";
    }

    public function halt()
    {
        return "SONY-PE5" . time();
    }
}

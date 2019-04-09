<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/9
 * Time: 11:15
 */

class ClientHummer
{
    private $hummer;

    public function main()
    {
        $this->hummer = new HummerH1();
        $this->hummer->run();
    }

}
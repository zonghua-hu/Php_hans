<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/26
 * Time: 16:29
 */
class Client
{
    public function main()
    {
        $ben = new Benx();
        $bwm = new BWM();

        $driver_ben = new Driver($ben);
        $result_ben = $driver_ben->drive();

        $driver_bwm = new Driver($bwm);
        $result_bwm = $driver_bwm->drive();

        print_r($result_ben,$result_bwm);
    }

}
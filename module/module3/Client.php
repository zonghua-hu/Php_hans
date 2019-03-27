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
        $result_ben = [];
        $result_bwm = [];

        $ben = new Benx();
        $bwm = new BWM();

        $driver_ben = new Driver($ben);
        if ($driver_ben) {
            $result_ben = $driver_ben->drive();
        }

        $driver_bwm = new Driver($bwm);
        if ($driver_bwm) {
            $result_bwm = $driver_bwm->drive();
        }
        return [$result_ben,$result_bwm];
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/10
 * Time: 15:17
 */

class BridgeClient
{
    public function main()
    {
        $house_corp = new HouseCorp(new House());
        $house_corp->makeMoney();
        echo '<br>';
        $shanzhai_corp = new ShanZhaiCorp(new IPod());
        $shanzhai_corp->makeMoney();
        echo '<br>';
        $clothes = new ShanZhaiCorp(new Clothes());
        $clothes->makeMoney();
        echo '<br>';
    }

}
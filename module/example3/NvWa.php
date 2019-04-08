<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/8
 * Time: 16:18
 */

class NvWa
{
    private $man;
    private $wemon;

    public function main()
    {
        $this->man = new MaleHumanFactory();
        $res_man_black = $this->man->createBlackHuman();//生产黑人男
        $res_man_black->getSex();
        $res_man_black->talk();
        $res_man_black->getColor();
        echo "生产黑人男完毕";
        $res_man_write = $this->man->createWriteHuman();//生产白人男
        $res_man_write->getColor();
        $res_man_write->talk();
        $res_man_write->getSex();
        echo "生产黑人男完毕";
        $this->wemon = new FemalHumanFactory();
        $res_memon_Black = $this->wemon->createWriteHuman();//生产白人女
        $res_memon_Black->getSex();
        $res_memon_Black->talk();
        $res_memon_Black->getColor();
        echo "生产黑人女完毕";
        $res_memon_Wemon = $this->wemon->createBlackHuman();//生产黑人女
        $res_memon_Wemon->getColor();
        $res_memon_Wemon->talk();
        $res_memon_Wemon->getSex();
        echo "生产白人女完毕";
    }


}
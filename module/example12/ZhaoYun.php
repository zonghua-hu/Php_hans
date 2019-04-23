<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/23
 * Time: 10:23
 */

class ZhaoYun
{
    private $context;
    private $blackenery;
    private $greenlight;
    private $backdoor;


    public function __construct()
    {
        $this->backdoor = new BackDoor();
        $this->blackenery = new BlackEnemy();
        $this->greenlight = new GreenLight();

    }
    public function main()
    {
        echo "刚到吴国拆开第一个锦囊~\n";
        $this->context = new Context($this->backdoor);
        $this->context->operate();//乔过老开后门
        echo "刘备乐不思蜀了~\n";
        $this->context = new Context($this->greenlight);
        $this->context->operate(); //找乔国老开后门
        echo "孙权的小兵追来了~~\n";
        $this->context = new Context($this->blackenery);
        $this->context->operate();
        echo "赵云计策用完，刘备回家了~~\n";
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/15
 * Time: 12:04
 */

class CommClient
{
    public function main()
    {
        $invoker = new Invoker();
        echo "客户要增加一项需求~";
        $invoker->setCommand('AddRequirementCommand');
        $invoker->action();
    }

    public function main_two()
    {
        $invoke_two = new Invoker();
        echo "客户要求删除一个页面";
        $invoke_two->setCommand('DeletePageCommand');
        $invoke_two->action();
    }

}
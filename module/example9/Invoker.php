<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/15
 * Time: 11:56
 */

class Invoker
{
    private $command;

    public function setCommand($obj)
    {
        if ($obj == 'DeletePageCommand') {
            $this->command = new DeletePageCommand();
        } elseif ($obj == 'AddRequirementCommand') {
            $this->command = new AddRequirementCommand();
        } else {
            $this->command = new AddRequirementCommand();
        }

    }

    public function action()
    {
        $this->command->execute();
    }

}
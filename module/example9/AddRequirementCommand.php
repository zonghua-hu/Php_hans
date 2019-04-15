<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/15
 * Time: 11:50
 */

class AddRequirementCommand extends Command
{
    public function execute()
    {
        $this->pg_group->find();
        $this->rg_group->delete();
        $this->rg_group->plan();
    }

}
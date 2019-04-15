<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/15
 * Time: 12:08
 */

class DeletePageCommand extends Command
{
    public function execute()
    {
        $this->rg_group->find();
        $this->rg_group->add();
        $this->rg_group->plan();
        $this->pg_group->add();
        $this->cg_group->Realization();
    }

}
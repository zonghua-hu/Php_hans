<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/15
 * Time: 11:36
 */

abstract class Command
{
    protected $rg_group;
    protected $pg_group;
    protected $cg_group;

    public function __construct()
    {
        $this->cg_group = new CodeGroup();

        $this->pg_group = new PageGroup();

        $this->rg_group = new RequirementGroup();

    }

    public static function getCommand()
    {
        return self::class;
    }

    public abstract function execute();
}
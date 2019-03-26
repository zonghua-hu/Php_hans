<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/26
 * Time: 16:19
 */

class Driver implements IDrive
{
    private $obj;

    /**
     * 构造方法里面判断是否是我们需要的对象
     * Driver constructor.
     * @param $obj
     */
    public function __construct($obj)
    {
        $this->obj = $obj;
        if ($obj instanceof Benx) {
            return $this->obj;
        } elseif ($obj instanceof BWM) {
            return $this->obj;
        } else {
            $this->obj = null;
        }
    }

    /**
     * 实现接口的抽象方法
     * @return mixed
     * @author Hans
     * @date 2019/3/26
     * @time 17:33
     */
    public function drive()
    {
        return $this->obj->carRun();
    }
}
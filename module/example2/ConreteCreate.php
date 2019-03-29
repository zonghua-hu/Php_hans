<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/29
 * Time: 11:32
 */

class ConreteCreate extends CreateProduct
{
    private $object;
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
        self::create();
    }

    public function create()
    {
        if (!$this->object instanceof ConcreteProduct) {
            $this->object = new ConcreteProduct($this->data);
        }
        return $this->object;
    }
}
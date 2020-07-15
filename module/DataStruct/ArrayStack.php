<?php
namespace module\DataStruct;

class ArrayStack
{
    /**
     * 栈
     * @var array
     */
    private $stack = array();
    /**
     * 当前的存储值
     * @var int
     */
    private $count = 0;

    /**
     * @Notes:入栈
     * @param $stackNum
     * @return bool
     * @User: Hans
     * @Date: 2020/5/14
     * @Time: 8:44 下午
     */
    public function push($stackNum)
    {
        $this->stack[$this->count] = $stackNum;
        $this->count ++;
        return true;
    }

    /**
     * @Notes:出栈
     * @param $stackPop
     * @return mixed|null
     * @User: Hans
     * @Date: 2020/5/14
     * @Time: 8:44 下午
     */
    public function pop()
    {
        if ($this->count == 0) {
            return  null;
        }
        return $this->stack[$this->count - 1];
    }

    /**
     * @Notes:返回栈总个数
     * @return int
     * @User: Hans
     * @Date: 2020/7/10
     * @Time: 10:03 上午
     */
    public function countStack()
    {
        return $this->count;
    }
}

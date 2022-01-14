<?php

/**
 * 书籍约束接口
 * Interface BookInterface
 */
interface BookInterface
{
    /**
     * 翻页方法
     * @return mixed
     */
    public function truePage();

    /**
     * 打开书操作
     * @return mixed
     */
    public function open();

    /**
     * 获取当前页码
     * @return mixed
     */
    public function getPage();

}

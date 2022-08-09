<?php

use Framework\Core\Container;

if (!function_exists('app')) {
    /**
     * 获取单例对象
     * @param $className
     * @return mixed|null
     */
    function app($className)
    {
        return Container::getInstanceWithSingleton($className);
    }
}

<?php

namespace BaseTrait;

use http\Exception\BadMethodCallException;

trait BaseFactoryTrait
{
    public static function getObject($type)
    {
        if (!isset(self::$map[$type])) {
            throw new BadMethodCallException("暂不支持当前类型");
        }
        if (!isset(self::$objects[$type])) {
            $className = self::$map[$type];
            self::$objects[$type] = new $className();
        }
        return self::$objects[$type];
    }
}

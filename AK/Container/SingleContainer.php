<?php

namespace Container;

use http\Exception\BadMethodCallException;
use http\Exception\InvalidArgumentException;
use ReflectionClass;

class SingleContainer
{
    private static $singleObject = [];

    public static function singleTon($instance)
    {
        if (!is_object($instance)) {
            throw new InvalidArgumentException('Object need');
        }
        $className = get_class($instance);
        if (!array_key_exists($className, self::$singleObject)) {
            self::$singleObject[$className] = $instance;
        }
    }

    public static function getSingleton($className)
    {
        return array_key_exists($className, self::$singleObject) ? self::$singleObject[$className] : null;
    }

    public static function unsetSingleton($className)
    {
        self::$singleObject[$className] = null;
    }

    public static function getInstance($className, $params = [])
    {
        $reflector = new \ReflectionClass($className);
        $constructor = $reflector->getConstructor();
        $diParams = [];
        if ($constructor) {
            foreach ($constructor->getParameters() as $parameter) {
                $class = $parameter->getClass();
                if ($class) {
                    $singleton = self::getSingleton($class->name);
                    $diParams[] = $singleton ? $singleton : self::getInstance($class->name);
                }
            }
        }
        $diParams = array_merge($diParams, $params);
        return $reflector->newInstanceArgs($diParams);
    }

    public static function run($className, $method, $params = [], $constructParams = [])
    {
        if (!class_exists($className)) {
            throw new BadMethodCallException("class $className is not found");
        }
        if (!method_exists($className, $method)) {
            throw new \BadMethodCallException("undefined method $method in $className");
        }
        $instance = self::getInstance($className, $constructParams);
        $reflector = new ReflectionClass($className);
        $reflectorMethod = $reflector->getMethod($method);
        $diParams = [];
        foreach ($reflectorMethod->getParameters() as $param) {
            $class = $param->getClass();
            if ($class) {
                $singleton = self::getSingleton($class->name);
                $diParams[] = $singleton ? $singleton : self::$singleObject[$class->name];
            }
        }
        return call_user_func_array([$instance, $method], array_merge($diParams, $params));
    }
}

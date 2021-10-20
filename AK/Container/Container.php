<?php

namespace Container;

class Container
{
    private static $instance = [];

    public static function getInstance($className, $params = [])
    {
        if (isset(self::$instance[$className])) {
            return self::$instance[$className];
        }
        $reflector = new \ReflectionClass($className);
        $constructor = $reflector->getConstructor();
        $diParams = [];
        if ($constructor) {
            foreach ($constructor->getParameters() as $parameter) {
                $class = $parameter->getClass();
                if ($class) {
                    $diParams[] = self::getInstance($class->name);
                }
            }
        }
        $diParams = array_merge($diParams, $params);
        self::$instance[$className] = $reflector->newInstanceArgs($diParams);
        return self::$instance[$className];
    }
}

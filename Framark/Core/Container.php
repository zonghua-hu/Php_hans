<?php

namespace Framework\Core;

use ReflectionClass;
use InvalidArgumentException;
use BadMethodCallException;

class Container
{
    /**
     * singleton instances.
     *
     * @var array
     */
    protected static $singleton = [];
    /**
     * create Dependency injection params.
     *
     * @param  array $params
     * @return array
     */
    protected static function getDiParams(array $params)
    {
        $di_params = [];
        foreach ($params as $param) {
            $class = $param->getClass();
            if ($class) {
                $singleton = self::getSingleton($class->name);
                $di_params[] = $singleton ? $singleton : self::getInstance($class->name);
            }
        }
        return $di_params;
    }
    /**
     * set a singleton instance.
     *
     * @param  object $instance
     * @param  string $name
     * @return void
     * @throws \InvalidArgumentException
     */
    public static function singleton($instance, $name = null)
    {
        if (! is_object($instance)) {
            throw new InvalidArgumentException("Object need!");
        }
        $class_name = $name == null ? get_class($instance) : $name;
        // singleton not exist, create
        if (! array_key_exists($class_name, self::$singleton)) {
            self::$singleton[$class_name] = $instance;
        }
    }
    /**
     * get a singleton instance.
     *
     * @param  string $class_name
     * @return mixed object or NULL
     */
    public static function getSingleton($class_name)
    {
        return array_key_exists($class_name, self::$singleton) ?
            self::$singleton[$class_name] : null;
    }
    /**
     * unset a singleton instance.
     *
     * @param  string $class_name
     * @return void
     */
    public static function unsetSingleton($class_name)
    {
        self::$singleton[$class_name] = null;
    }
    /**
     * register class, instantiate class, set instance to singleton.
     *
     * @param  string $abstract abstract class name
     * @param  string $concrete concrete class name, if NULL, use abstract class name
     * @return void
     */
    public static function register($abstract, $concrete = null)
    {
        if ($concrete == null) {
            $instance = self::getInstance($abstract);
            self::singleton($instance);
        } else {
            $instance = self::getInstance($concrete);
            self::singleton($instance, $abstract);
        }
    }
    /**
     * get Instance from reflection info.
     *
     * @param  string  $class_name
     * @param  array  $params
     * @return object
     */
    public static function getInstance($class_name, $params = [])
    {
        // get class reflector
        $reflector = new ReflectionClass($class_name);
        // get constructor
        $constructor = $reflector->getConstructor();
        // create di params
        $di_params = $constructor ? self::getDiParams($constructor->getParameters()) : [];
        $di_params = array_merge($di_params, $params);
        // create instance
        return $reflector->newInstanceArgs($di_params);
    }
    /**
     * get Instance, if instance is not singleton, set it to singleton.
     *
     * @param  string  $class_name
     * @param  array  $params
     * @return object
     */
    public static function getInstanceWithSingleton($class_name, $params = [])
    {
        // is a singleton instance?
        if (null != ($instance = self::getSingleton($class_name))) {
            return $instance;
        }
        $instance = self::getInstance($class_name, $params);
        self::singleton($instance);
        return $instance;
    }
    /**
     * run class method.
     *
     * @param  string $class_name
     * @param  string $method
     * @param  array  $params
     * @param  array  $construct_params
     * @return mixed
     * @throws \BadMethodCallException
     */
    public static function run($class_name, $method, $params = [], $construct_params = [])
    {
        // class exist ?
        if (! class_exists($class_name)) {
            throw new BadMethodCallException("Class $class_name is not found!");
        }
        // method exist ?
        if (! method_exists($class_name, $method)) {
            throw new BadMethodCallException("undefined method $method in $class_name !");
        }
        // create instance
        $instance = self::getInstance($class_name, $construct_params);
        /******* method Dependency injection *******/
        // get class reflector
        $reflector = new ReflectionClass($class_name);
        // get method
        $reflectorMethod = $reflector->getMethod($method);
        // create di params
        $di_params = self::getDiParams($reflectorMethod->getParameters());
        // run method
        return call_user_func_array([$instance, $method], array_merge($di_params, $params));
    }
}

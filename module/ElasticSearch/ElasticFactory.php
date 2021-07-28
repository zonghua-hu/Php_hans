<?php


namespace Elasticsearch;

use Exception;

class ElasticFactory implements ElasticDto
{
    protected $errorCode = 401;
    /**
     * 用来保存es实例化后的对象的数组
     * @var array
     */
    private static $elasticArr = [];

    /**
     * @Notes:抽象工厂获取实例化对象
     * @param $class
     * @return mixed
     * @User: Hans
     * @Date: 2021/3/9
     * @Time: 下午5:52
     * @throws Exception
     */
    public function getInstance($class)
    {
        if (!class_exists(__NAMESPACE__ . '\\' . $class)) {
            throw new Exception("{$class}".'类不存在，请检查', $this->errorCode);
        }
        if (!isset(self::$elasticArr[$class])) {
            $className = __NAMESPACE__ . '\\' . $class;
            self::$elasticArr[$class] = new $className();
        }
        return self::$elasticArr[$class];
    }
}

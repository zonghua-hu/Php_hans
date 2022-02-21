<?php

namespace Dto;

use think\Log;

abstract class BaseDto
{
    /**
     * 返回所有的属性
     * @var int
     */
    protected $all = 1;

    /**
     * 仅返回public和protected属性
     * @var int
     */
    protected $onlyPublicAndProtected = 2;

    /**
     * 仅返回public属性
     * @var int
     */
    protected $onlyPublic = 3;
    /**
     * 是否下划线转驼峰
     * @var bool
     */
    protected $isUnderScore2Camel = false;
    /**
     * 要被过滤的属性
     * @var array
     */
    protected $filter = [];

    /**
     * 驼峰转下划线
     * @param $str
     * @return string
     */
    protected function toUnderScore($str)
    {
        $tempStr = preg_replace_callback('/([A-Z]+)/', function ($match) {
            return '_' . strtolower($match[0]);
        }, $str);
        return trim(preg_replace('/_{2,}/', '_', $tempStr), '_');
    }

    /**
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this);
    }
    /**
     * 返回对应类的属性名列表, 同时排除BaseDto及其父类的属性
     * @param int $range
     * @return array
     */
    protected function attributes($range = 1)
    {
        $baseDtoClass = new \ReflectionClass(BaseDto::class);
        $class = new \ReflectionClass($this);
        $names = [];
        foreach ($class->getProperties() as $property) {
            if (!$property->isStatic()) {
                $baseDtoProperties = self::getColumn($baseDtoClass->getProperties(), 'name');
                //排除BaseDto及其父类的属性
                if (!in_array($property->getName(), $baseDtoProperties)) {
                    switch ($range) {
                        //返回所有属性
                        case $this->all:
                            $names[] = $property->getName();
                            break;
                        //只返回public和protected属性
                        case $this->onlyPublicAndProtected:
                            if ($property->getModifiers() != \ReflectionProperty::IS_PRIVATE) {
                                $names[] = $property->getName();
                            }
                            break;
                        //只返回public属性
                        case $this->onlyPublic:
                            if ($property->getModifiers() == \ReflectionProperty::IS_PUBLIC) {
                                $names[] = $property->getName();
                            }
                            break;
                    }
                }
            }
        }
        return $names;
    }

    /**
     * 过滤对象中的null值
     * @return $this
     */
    public function nullRemove()
    {
        foreach ($this->attributes($this->onlyPublicAndProtected) as $value) {
            if (is_null($this->$value)) {
                $this->filter[] = $value;
            }
        }
        return $this;
    }
    /**
     * 自动过滤空属性
     * @param array $filter 如果属性在 $filter 数组中，且为空的话，将会自动过滤
     * @param array $remain 如果属性在 $remain 数组中，且为空的话，将会保留
     * @return $this
     */
    public function holdEmpty($filter = [], $remain = [])
    {
        if (!empty($filter) && !empty($remain)) {
            Log::info(['$filter 和 $remain 不能同时不为空！']);
            return $this;
        }
        //拿到所有的public和protected属性
        $attributes = $this->attributes($this->onlyPublicAndProtected);
        foreach ($attributes as $attribute) {
            //只要是空值就过滤
            if (empty($filter) && empty($remain)) {
                if (empty($this->$attribute)) {
                    $this->filter[] = $attribute;
                    unset($this->$attribute);
                }
            } elseif (!empty($filter)) {
                //如果属性在 $filter 数组中，且为空的话，将会过滤
                if (empty($this->$attribute) && in_array($attribute, $filter)) {
                    $this->filter[] = $attribute;
                    unset($this->$attribute);
                }
            } elseif (!empty($remain)) {
                //如果属性在 $remain 数组中，且为空的话，将会保留
                if (empty($this->$attribute) && !in_array($attribute, $remain)) {
                    $this->filter[] = $attribute;
                    unset($this->$attribute);
                }
            }
        }

        return $this;
    }
    /**
     * @param $array
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    private static function getValue($array, $key, $default = null)
    {
        if ($key instanceof \Closure) {
            return $key($array, $default);
        }

        if (is_array($key)) {
            $lastKey = array_pop($key);
            foreach ($key as $keyPart) {
                $array = static::getValue($array, $keyPart);
            }
            $key = $lastKey;
        }

        if (is_array($array) && (isset($array[$key]) || array_key_exists($key, $array))) {
            return $array[$key];
        }

        if (($pos = strrpos($key, '.')) !== false) {
            $array = static::getValue($array, substr($key, 0, $pos), $default);
            $key = substr($key, $pos + 1);
        }

        if (is_object($array)) {
            // 如果属性不存在，或者没有实现_get()，这个返回就会有问题
            return $array->$key;
        } elseif (is_array($array)) {
            return (isset($array[$key]) || array_key_exists($key, $array)) ? $array[$key] : $default;
        }

        return $default;
    }
    /**
     * @param $array
     * @param $name
     * @param bool $keepKeys
     * @return array
     */
    private static function getColumn($array, $name, $keepKeys = true)
    {
        $result = [];
        if ($keepKeys) {
            foreach ($array as $k => $element) {
                $result[$k] = static::getValue($element, $name);
            }
        } else {
            foreach ($array as $element) {
                $result[] = static::getValue($element, $name);
            }
        }
        return $result;
    }

    /**
     * @return array
     * 对象转数组：自动转为下划线格式和表匹配
     */
    public function toArray()
    {
        $attributes = $this->attributes();
        $arr = [];
        foreach ($attributes as $attribute) {
            if (is_null($this->$attribute)) {
                continue;
            }
            $getter = 'get' . implode(array_map('ucfirst', explode('_', trim($attribute))));
            $key = $this->toUnderScore($attribute);
            if (method_exists($this, $getter)) {
                $arr[$key] = $this->$getter($attribute);
            }
        }
        return $arr;
    }

    /**
     * @return string
     * 将对象转字符串：会匹配表中的字段转为下划线的形式
     */
    public function toUnderlineJson(): string
    {
        $toJson = [];
        $attributes = $this->attributes();
        foreach ($attributes as $attribute) {
            if (!is_null($this->$attribute)) {
                $key = $this->toUnderScore($attribute);
                $toJson[$key] = $this->$attribute;
            }
        }
        return json_encode($toJson);
    }
}
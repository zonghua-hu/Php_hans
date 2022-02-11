<?php

namespace BaseTrait;

use BadMethodCallException;
use Core\PropertyType;
use http\Exception\BadConversionException;

/**
 * Trait PropertyTrait
 * @package BaseTrait
 */
trait PropertyTrait
{
    /**
     * 属性集合
     * @var array
     */
    protected static $propertyTypes = [];

    /** @var object 要设置的类 */
    protected $class;

    /** @var string 类名 */
    protected $className;

    /**
     * PropertyTrait constructor.
     * @param null $data
     */
    public function __construct($data = null)
    {
        $data && $this->fill($data);
    }

    /**
     * 数组自动填充到对象的属性
     * @param $data
     * @return $this
     */
    public function fill($data)
    {
        if (!is_array($data) && !is_object($data)) {
            throw new \TypeError('填充数据错误');
        }
        $this->fillTypeData($data);
        return $this;
    }

    /**
     * 填充数据前置后+填充+后置操作
     * @param $data
     */
    protected function fillTypeData($data)
    {
        $this->beforeFill($data);
        $this->fillData($data);
        $this->afterFill();
    }

    /**
     * 填充前置动作
     * @param $data
     */
    protected function beforeFill(&$data)
    {
    }

    /**
     * 填充后置动作-自定义
     */
    protected function afterFill()
    {
    }

    /**
     * 填充数组数据到对象属性
     * @param array $data
     */
    public function fillData($data = [])
    {
        if (!$data) {
            return;
        }
        $propertyTypes = self::getPropertyTypes();
        foreach ($data as $propertyName => $propertyValue) {
            // 不在类属性里的忽略
            if (!array_key_exists($propertyName, $propertyTypes)) {
                continue;
            }
            try {
                $this->setPropertyValue($propertyValue, $propertyName, $propertyTypes[$propertyName]);
            } catch (\Throwable $e) {
                throw new BadMethodCallException("{$propertyName}属性填充错误");
            }
        }
    }

    /**
     * 实例化对象
     * @param string $className
     * @param $params
     * @return mixed
     */
    private function newInstance(string $className, $params)
    {
        return new $className($params);
    }

    /**
     * 设置属性值
     * @param $propertyValue
     * @param string $propertyName
     * @param PropertyType $propertyType
     * @throws \ReflectionException
     */
    protected function setPropertyValue($propertyValue, string $propertyName, PropertyType $propertyType)
    {
        $reflectionProperty = new \ReflectionProperty($this->class, $propertyName);
        if (!$reflectionProperty->isPublic()) {
            return;
        }
        if ($propertyType->is_collection) {
            $values = [];
            foreach ($propertyValue as $item) {
                if ($propertyType->is_class) {
                    $values[] = $this->newInstance($propertyType->type, $item);
                } else {
                    $values[] = $this->transform($item, $propertyType->type);
                }
            }
            $reflectionProperty->setValue($this->class, $values);
        } else {
            $value = null;
            if ($propertyType->is_class) {
                $value = $this->newInstance($propertyType->type, $propertyValue);
            } else {
                $value = $this->transform($propertyValue, $propertyType->type);
            }
            $reflectionProperty->setValue($this->class, $value);
        }
    }

    /**
     * 类型转换
     * @param $value
     * @param string $type
     * @return array|bool|float|int|string|null
     */
    private function transform($value, string $type)
    {
        if (is_null($value)) {
            return null;
        }
        switch ($type) {
            case 'string':
                return (string)$value;
            case 'int':
                return (int)$value;
            case 'bool':
                return (bool)$value;
            case 'float':
                return (float)$value;
            case 'array':
                return (array)$value;
            default:
                throw new BadConversionException("Call to transform type {$type}=>{$value}");
        }
    }

    /**
     * 获取属性类型
     * @return array
     */
    public function getPropertyTypes(): array
    {
        $classPropertyTypes = &self::$propertyTypes[$this->className];
        if (!empty($classPropertyTypes)) {
            return $classPropertyTypes;
        }
        $classPropertyTypes = [];
        $reflection = new \ReflectionClass($this->class);
        $properties = $reflection->getProperties();
        foreach ($properties as $property) {
            if (!$property->isPublic()) {
                continue;
            }
            $name = $property->getName();
            $propertyType = $this->parseProperty($property->getDocComment(), $reflection->getNamespaceName());
            self::$propertyTypes[$this->className][$name] = $propertyType;
        }
        return self::$propertyTypes[$this->className];
    }

    /**
     * 解析类属性-类型
     * @param string $doc
     * @param string $namespace
     * @return PropertyType
     */
    protected function parseProperty(string $doc, string $namespace): PropertyType
    {
        $type = new PropertyType();
        // 从注释中解析出类型
        preg_match("/@var \??(.*?)(\[\])?[\|\s\*]/", $doc, $matches);
        $matchType = $matches[1] ?? '';
        //如果注释没有命名空间, 则查看是否再当前命名空间下存在该类
        if (!class_exists($matchType)) {
            $classType = "{$namespace}\\{$matchType}";
            if (class_exists($classType)) {
                $matchType = $classType;
            }
        }
        $type->type = $matchType;
        $type->is_class = class_exists($matchType);
        $type->is_collection = isset($matches[2]) && '[]' === $matches[2];

        return $type;
    }
    /**
     * 自动设置属性
     * @param $name
     * @param $arguments
     * @return $this
     */
    public function __call($name, $arguments)
    {
        if (strpos($name, 'get') !== false) {
            $field = lcfirst(ltrim($name, 'get'));
            if (property_exists($this, $field)) {
                return $this->{$field};
            }
        } elseif (strpos($name, 'set') !== false) {
            $field = lcfirst(ltrim($name, 'set'));
            if (property_exists($this, $field)) {
                $this->{$field} = array_key_exists(0, $arguments) ? $arguments[0] : null;
                return $this;
            }
        }
        $className = get_class($this);
        throw new BadMethodCallException("Call to undefined method {$className}::{$name}()");
    }

    /**
     * 对象转数组
     * @return array
     */
    public function toArray(): array
    {
        return (array)json_decode(json_encode($this), true);
    }

    /**
     * 对象转json字符串
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this);
    }
    /**
     * 将多维数组转为当前对象数组
     * @param array $list
     * @return array
     */
    public static function fromList(array $list): array
    {
        $data = [];
        foreach ($list as $item) {
            $data[] = new static($item);
        }
        return $data;
    }
}

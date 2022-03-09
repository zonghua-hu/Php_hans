<?php

namespace BaseTrait;

use Dto\PropertyDocParser;

trait FillDataTrait
{
    /**
     * 将数组配置注入实体对象
     * FillDataTrait constructor.
     * @param null $data
     */
    public function __construct($data = null)
    {
        $data && $this->fill($data);
    }

    /**
     * 将数组配置注入实体对象
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
     * @param $data
     */
    protected function fillTypeData($data)
    {
        $this->beforeFill($data);
        $parser = new PropertyDocParser($this);
        $parser->fillTypeData($data);
        $this->afterFill();
    }

    /**
     * 填充前操作
     */
    protected function beforeFill(&$data)
    {
    }

    /**
     * 填充后操作
     */
    protected function afterFill()
    {
    }

    /**
     * 将对象数组转为当前对象数组
     * @param $data
     * @return static
     */
    public static function fromItem($data): self
    {
        return new static($data);
    }

    /**
     * 将多维数组转为当前对象数组
     * @param array $list
     * @param bool $remainOriginalKey
     * @return array
     */
    public static function fromList(array $list, bool $remainOriginalKey = false): array
    {
        $data = [];
        foreach ($list as $key => $item) {
            if ($remainOriginalKey) {
                $data[$key] = new static($item);
            } else {
                $data[] = new static($item);
            }
        }
        return $data;
    }
}

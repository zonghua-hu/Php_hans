<?php

namespace Dto;

class PropertyType
{
    /** @var string 字段类型 */
    public $type = 'string';

    /** @var bool 是否是类 */
    public $is_class = false;

    /** @var bool 是否是集合 */
    public $is_collection = false;

    /** @var bool 是否允许为null */
    public $allows_null = true;
}

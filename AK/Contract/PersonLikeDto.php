<?php

namespace Contract;

use Dto\BaseDto;

class PersonLikeDto extends BaseDto
{
    /**
     * 爱好名字
     * @var string
     */
    public $likeName;
    /**
     * 爱好标题
     * @var string
     */
    public $likeTitle;
    /**
     * 爱好类别
     * @var int
     */
    public $likeType;
}

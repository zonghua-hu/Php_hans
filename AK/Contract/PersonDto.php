<?php

namespace Contract;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * @method getName()
 * @method PersonDto setName(string $val)
 * @method getAge()
 * @method PersonDto setAge(int $val)
 * @method getLike()
 * @method PersonDto setLike(string $val)
 * @method getSex()
 * @method PersonDto setSex(Boolean $val)
 * Class PersonDto
 * @package Contract
 */
class PersonDto
{
    use BaseTrait;
    /**
     * 姓名
     * @var string $name
     */
    public $name;
    /**
     * 年龄
     * @var int
     */
    public $age;
    /**
     * 爱好
     * @var string
     */
    public $like;
    /**
     * 性别
     * @var bool
     */
    public $ses;

    public function create(array $aprams)
    {
        return $this->setAge($aprams['age'] ?? 0)
            ->setSex($aprams['sex'] ?? true);
    }
}

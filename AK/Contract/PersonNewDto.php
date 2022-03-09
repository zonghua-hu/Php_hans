<?php

namespace Contract;

class PersonNewDto extends \Dto\BaseDto
{
    use PersonDto;

    /**
     * @var PersonLikeDto[] $likes
     */
    public $likes;
}

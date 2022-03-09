<?php

namespace Dto;

use BaseTrait\ArrayAccessTrait;
use BaseTrait\FillDataTrait;
use BaseTrait\PropertyToArrayTrait;

abstract class BaseDto implements \ArrayAccess
{
    use ArrayAccessTrait;
    use PropertyToArrayTrait;
    use FillDataTrait;
}

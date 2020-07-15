<?php

namespace Responsiby;

use PharIo\Manifest\Requirement;

class RequireHandler extends Handler
{
    private $requireData;

    private $strName = 'Abc1019';

    public function __construct(array $data, Handler $handler = null)
    {
        parent::__construct($handler);
        $this->requireData = $data;
    }

    protected function processing(Requirement $requirement)
    {
        $key = sprintf('%s%s', $this->strName, time());
        if (isset($this->requireData[$key])) {
            return $this->requireData[$key];
        }
        return null;
    }
}

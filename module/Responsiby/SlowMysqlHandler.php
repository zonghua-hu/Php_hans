<?php

namespace Responsiby;

use PharIo\Manifest\Requirement;

class SlowMysqlHandler extends Handler
{
    public function __construct(Handler $handler)
    {
        parent::__construct($handler);
    }
    protected function processing(Requirement $requirement)
    {
        return 'mysql connect is succeed';
    }
}

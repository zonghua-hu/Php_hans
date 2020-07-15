<?php

namespace Responsiby;

use PharIo\Manifest\Requirement;

abstract class Handler
{
    private $success = null;

    public function __construct(Handler $handler)
    {
        $this->success = $handler;
    }

    final public function handle(Requirement $requirement)
    {
        $process = $this->processing($requirement);
        if ($process == null) {
            if ($this->success != null) {
                $process = $this->success->handle($requirement);
            }
        }
        return $process;
    }

    abstract protected function processing(Requirement $requirement);
}

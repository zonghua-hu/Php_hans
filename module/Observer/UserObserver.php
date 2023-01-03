<?php

namespace Observer;

use SplSubject;

class UserObserver implements \SplObserver
{
    private $changeUsers = [];

    public function update(SplSubject $subject)
    {
        $this->changeUsers[] = clone $subject;
    }

    /**
     * @return array
     */
    public function getChangeUsers(): array
    {
        return $this->changeUsers;
    }
}
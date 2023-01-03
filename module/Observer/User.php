<?php

namespace Observer;

use SplObserver;

class User implements \SplSubject
{
    private $email;
    private $observer;
    public function __construct()
    {
        $this->observer = new \SplObjectStorage();
    }

    public function attach(SplObserver $observer)
    {
        $this->observer->attach($observer);
    }
    public function detach(SplObserver $observer)
    {
        $this->observer->detach($observer);
    }
    public function changeEmail(string $email)
    {
        $this->email = $email;
        $this->notify();
    }
    public function notify()
    {
        /** @var \SplObserver $obj */
        foreach ($this->observer as $obj) {
            $obj->update($this);
        }
    }

}
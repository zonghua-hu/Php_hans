<?php

namespace DataMapper;

class User
{
    private $email;
    private $userName;

    public function __construct(string $email, string $user)
    {
        $this->userName = $user;
        $this->email = $email;
    }

    /**
     * @param array $state
     * @return User
     */
    public static function formState(array $state): User
    {
        return new self($state['email'], $state['user_name']);
    }

    /**
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
}

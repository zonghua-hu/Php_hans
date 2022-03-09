<?php

namespace DataMapper;

use http\Exception\InvalidArgumentException;

class UserMapper
{
    private $adapter;

    /**
     * UserMapper constructor.
     * @param StorageAdapter $adapter
     */
    public function __construct(StorageAdapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param int $id
     * @return User
     */
    public function findById(int $id)
    {
        $result = $this->adapter->find($id);
        if (!$result) {
            echo "user:{$id} is not fund";
            die;
//            throw new InvalidArgumentException("user:{$id} is not fund");
        }
        return $this->mapRowToUser($result);
    }

    /**
     * @param array $data
     * @return User
     */
    public function mapRowToUser(array $data)
    {
        return User::formState($data);
    }
}

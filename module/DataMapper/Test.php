<?php

require_once 'User.php';
require_once 'UserMapper.php';
require_once 'StorageAdapter.php';

$result = new \DataMapper\StorageAdapter(['user_name' => 'hans', 'email' => '1078743005@qq.com']);
$mapper = new \DataMapper\UserMapper($result);

$user = $mapper->findById(1);
print_r($user);

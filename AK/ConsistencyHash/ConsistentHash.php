<?php

namespace ConsistencyHash;

interface ConsistentHash
{
    public function getHash($strCode);

    public function addServer($server);

    public function removeServer($server);

    public function searchKey($key);
}

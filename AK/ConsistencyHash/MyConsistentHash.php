<?php

namespace ConsistencyHash;

use http\Exception\BadMethodCallException;

class MyConsistentHash implements ConsistentHash
{
    public $serverList = array();
    public $virtualList = array();
    public $virtualNode = 5;

    /**
     * 计算哈希值
     * @param $strCode
     * @return string
     */
    public function getHash($strCode)
    {
        $strCode = md5($strCode);
        return sprintf("%u", crc32($strCode));
    }

    /**
     * 添加服务器节点
     * @param $server
     * @return bool
     */
    public function addServer($server)
    {
        if (!isset($this->serverList[$server])) {
            for ($i = 0; $i < $this->virtualNode; $i++) {
                $pos = $this->getHash($server . "-" . $i);
                $this->virtualList[$pos] = $server;
                $this->serverList[$server][] = $pos;
            }
            ksort($this->virtualList, SORT_NUMERIC);
        }
        return true;
    }

    /**
     * 移除服务器节点
     * @param $server
     * @return bool
     */
    public function removeServer($server)
    {
        if (isset($this->serverList[$server])) {
            foreach ($this->serverList[$server] as $pos) {
                unset($this->virtualList[$pos]);
            }
            unset($this->serverList[$server]);
        }
        return true;
    }

    /**
     * 获取指定key的服务器所在
     * @param $key
     * @return mixed
     */
    public function searchKey($key)
    {
        $point = $this->getHash($key);
        $finalServer = current($this->virtualList);
        foreach ($this->virtualList as $pos => $server) {
            if ($point <= $pos) {
                $finalServer = $server;
                break;
            }
        }
        reset($this->virtualList);
        return $finalServer;
    }

    /**
     * 初始化服务器节点
     * @param array $serverNode
     * @return array
     */
    public function initServer(array $serverNode)
    {
        $succeed = 0;
        $failServer = [];
        foreach ($serverNode as $server) {
            if (!$this->addServer($server)) {
                echo "添加服务节点失败";
                $failServer[] = $server;
                //告警+通知负责人
                continue;
            }
            $succeed++;
        }
        return [$succeed, $failServer];
    }
}

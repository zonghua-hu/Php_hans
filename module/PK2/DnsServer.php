<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/16
 * Time: 11:49
 */

abstract class DnsServer
{
    private $upserver;

    public final function resolve($str_domain)
    {
        $recoder = null;

        if ($this->isLocal($str_domain)) {
            $recoder = $this->getsRecoder($str_domain);
        } else {
            $recoder = $this->upserver->getsRecoder($str_domain);
        }
        return $recoder;
    }

    public function setUpperServer(DnsServer $server)
    {
        $this->upserver = $server;
    }

    protected abstract function  isLocal($str_domain);

    protected function getsRecoder($str_domain)
    {
        $recoder = new Recorder();
        $recoder->setIp($this->getIpAddress());
        $recoder->setDomain($str_domain);
        return $recoder;
    }

    private function getIpAddress()
    {
        $rand_ip = rand(1,100);
        $str_ip = $rand_ip."255".$rand_ip;
        return $str_ip;
    }




}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/16
 * Time: 11:28
 */

class Recorder
{
    //域名
    private $domain;
    //IP地址
    private $ip;
    //宿主
    private $own;

    public function setDomain($str_domain)
    {
        $this->domain = $str_domain;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function getIp()
    {
        return $this->ip;
    }

    public function setIp($str_ip)
    {
        $this->ip = $str_ip;
    }

    public function getOwener()
    {
        return $this->own;
    }

    public function setOwner($str_own)
    {
        $this->own = $str_own;
    }

    public function getString()
    {
        $str = "域名：".$this->domain.PHP_EOL;
        $str .= "ip:".$this->ip.PHP_EOL;
        $str .= "解析着:".$this->own;
        return $str;
    }

}
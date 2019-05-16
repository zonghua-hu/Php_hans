<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/16
 * Time: 12:12
 */

class SHDnsServer extends DnsServer
{
    protected function getsRecoder($str_domain)
    {
       $recoder = parent::getsRecoder($str_domain);
       $recoder->setOwner("上海dns服务器");
       return $recoder;
    }

    protected function isLocal($str_domain)
    {
        // TODO: Implement isLocal() method.
        return $str_domain.".sh.cn";
    }

}
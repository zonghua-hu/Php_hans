<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/16
 * Time: 12:19
 */

class DnsClient
{
    private $HS_server;

    private $China_server;

    private $Top_server;

    public function __construct()
    {
        $this->HS_server = new SHDnsServer();

        $this->China_server = new ChinaDnsServer();

        $this->Top_server = new TopDnsServer();

        $this->China_server->setUpperServer($this->Top_server);

        $this->HS_server->setUpperServer($this->China_server);
    }

    public function main($str_domain)
    {
        while (true) {
            echo "请输入域名：(以N结尾)";
            $str_domain = trim($str_domain);
            $new_domain = strstr($str_domain,".com");
            $recoder = $this->HS_server->resolve($new_domain);
            return  "解析结果".$recoder;
        }
    }

}
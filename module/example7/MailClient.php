<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/12
 * Time: 17:55
 */

class MailClient
{
    const MAX = 10;

    private $mail;

    public function __construct()
    {
        $this->mail = new Mail();
    }

    public function main()
    {
        $this->mail->setTail('中国银行版权所有');
        for ($i = 0;$i<self::MAX;$i++) {
            $this->mail->setApplication(self::getRandString(5)."先生（女士）");
            $this->mail->setReceiver(self::getRandString(8)."@qq.com");
            self::sendMail($this->mail);
        }
    }
    /**
     * 发送邮件
     * @param $obj
     * @author Hans
     * @date 2019/4/12
     * @time 18:14
     */
    private function sendMail($obj)
    {
        if (!$obj instanceof Mail) {
            $obj = new Mail();
        }
        $this->mail = $obj;
        echo "标题：".$this->mail->getSubject()."\t收件人".$this->mail->getReceiver()."\t发送成功~";
    }
    /**
     * 生成指定长度的随机字符串
     * @param $length
     * @return bool|string
     * @author Hans
     * @date 2019/4/12
     * @time 18:07
     */
    private function getRandString($length)
    {
        return $str_rand = substr(str_shuffle("ABCDEFGHIGHLMNOPQRSTUVWXYZ"),$length);
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/8
 * Time: 16:27
 */

class Controller
{
    /**
     * 成功的跳转方法
     * @Method success
     * @param $url
     * @param string $msg
     * @email zonghua.hu@weicheche.cn
     * @author Hans
     * @date 2019/1/10
     * @time 11:37
     */
    public function success($url, $msg = '')
    {
        $this->jump($url, $msg, true);
    }
    /**
     * 失败的跳转方法
     * @Method error
     * @param $url
     * @param string $msg
     * @email zonghua.hu@weicheche.cn
     * @author Hans
     * @date 2019/1/10
     * @time 11:37
     */
    public function error($url, $msg = '')
    {
        $this->jump($url, $msg, false);
    }
    /**
     * 跳转方法
     * @Method jump
     * @param $url
     * @param $msg
     * @param int $time
     * @param bool $flag
     * @email zonghua.hu@weicheche.cn
     * @author Hans
     * @date 2019/1/10
     * @time 11:38
     */
    public function jump($url, $msg, $time = 3, $flag = true)
    {
        if ($msg == '') {
            header("localtion:{$url}");
        } else {
            if ($flag)
                $path = '<img src="/public/img/success.jpg">';
            else
                $path = '<img src="/public/img/error.jpg">';
            echo <<<jump
             <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <meta http-equiv="refresh" content="{$time};URL={$url}" /> 
            <title>无标题文档</title>
            <style type="text/css">
            body{
                text-align:center;
                font-size:20px;
                background-color:#F90;
                color:#F00;
                padding-top:30px;
                font-family:'微软雅黑'
            }
            </style>
            </head>
            
            <body>
            {$path}
            {$msg}
            </body>
            </html>  
jump;

        }

    }
}


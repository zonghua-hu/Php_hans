<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/9
 * Time: 10:45
 */

class SignInfoFactory
{
    private static $obj;
    private static $pool = array();

    public function __construct($obj)
    {
        if (!$obj instanceof SignInfo) {
            $obj = new SignInfo();
        }
        self::$obj = $obj;
    }

    public static function getObjFactory($key)
    {
        $result = null;
        if (!in_array($key,array_keys(self::$pool))) {
            self::makeLog('新建对象，置入池中~');
            $result = new SignInfoPool($key);
            self::$pool[$key] = $result;
        } else {
            $result = self::$pool[$key];
            self::makeLog('从池子中取出已有对象~');
        }
        return $result;
    }

    private static function makeLog($msg){
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS,1);
        $file = $trace[0]['file'];
        $line = $trace[0]['line'];
        echo "[file:{$file};line:{$line}]#{$msg}";
    }





}
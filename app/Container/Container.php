<?php
namespace Container;

class Container
{
    /**
     * 注入模型类
     * @var string
     */
    private static $modelPath = APP_PATH . "models";
    /**
     * 注入逻辑类
     * @var string
     */
    private static $logicPath = APP_PATH . "logics";

    /**
     * @Notes:将指定类文件注入容器Di
     * @param $di
     * @User: Hans
     * @Date: 2020/9/10
     * @Time: 5:27 下午
     */
    public static function registerContainer($di)
    {
        $registerDir = [
            'model' => self::$modelPath,
            'logic' => self::$logicPath
        ];
        foreach ($registerDir as $item) {
            $files = [];
            self::tree($files, $item);
            foreach ($files as $classFile) {
                $length = strripos($classFile, '.') - strripos($classFile, '/') - 1;
                $registerName = substr($classFile, strripos($classFile, '/') + 1, $length);
                if ($registerName == 'CustomerGroup') {
                    self::autoLoad($item.$classFile);
                    spl_autoload_register('self::autoLoad');
                    $di->set($registerName, new $registerName());
                }
            }
        }
    }

    /**
     * @Notes:类的自动注册
     * @param $className
     * @User: Hans
     * @Date: 2020/9/10
     * @Time: 5:15 下午
     */
    private static function autoLoad($className)
    {
        require_once $className;
    }

    /**
     * @Notes:获取类文件
     * @param $arr_file
     * @param $directory
     * @param string $dir_name
     * @User: Hans
     * @Date: 2020/9/10
     * @Time: 5:22 下午
     */
    private static function tree(&$arr_file, $directory, $dir_name = '')
    {
        $mydir = dir($directory);
        while ($file = $mydir->read()) {
            if((is_dir("$directory/$file")) AND ($file != ".") AND ($file != "..")) {
                self::tree($arr_file, "$directory/$file", "$dir_name/$file");
            } else if(($file != ".") AND ($file != "..")) {
                $arr_file[] = "$dir_name/$file";
            }
        }
        $mydir->close();
    }

    public static function app($make, $parameters = [])
    {
        return \Phalcon\Di::getDefault()->get($make);
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/8
 * Time: 14:47
 */
class Framework
{
    public static function run()
    {
        self::initConst();
        self::initConfig();
        self::initRoutes();
        self::initRegisterAutoLoad();
        self::initDispatch();
    }
    /**
     * 定义常量路径
     * @Method initConst
     * @email zonghua.hu@weicheche.cn
     * @author Hans
     * @date 2019/1/8
     * @time 14:49
     */
    private static function initConst()
    {
        define('DS',DIRECTORY_SEPARATOR);                     //定义目录分隔符
        define('ROOT_PATH',getcwd().DS);                      //根目录
        define('APP_PATH',ROOT_PATH.'app'.DS);                //定义app目录
        define('FRAMARK_PATH',ROOT_PATH.'Framark'.DS);        //定义框架目录
        define('PUBLIC_PATH',ROOT_PATH.'public'.DS);          //public目录
        define('CONFIG_PATH',APP_PATH.'Config'.DS);          //配置目录
        define('CONTROLLER_PATH',APP_PATH.'controllers'.DS); //controllers目录
        define('LOGIC_PATH',APP_PATH.'logic'.DS);            //logic
        define('MODEL_PATH',APP_PATH.'models'.DS);           //models
        define('VIEW_PATH',APP_PATH.'views'.DS);             //view
        define('CORE_PATH',FRAMARK_PATH.'Core'.DS);          //core目录
        define('LIB_PATH',FRAMARK_PATH.'Lib'.DS);            //Lib目录
    }
    /**
     * 导入配置文件
     * @Method initCinfig
     * @email zonghua.hu@weicheche.cn
     * @author Hans
     * @date 2019/1/8
     * @time 15:22
     */
    private static function initConfig()
    {
        $GLOBALS['config'] = require  CONFIG_PATH.'config.php';
    }
    /**
     * 确定路由
     * @Method initRoutes
     * @email zonghua.hu@weicheche.cn
     * @author Hans
     * @date 2019/1/8
     * @time 15:58
     */
    private static function initRoutes()
    {
        $p = isset($_REQUEST['p'])?$_REQUEST['p']:$GLOBALS['config']['app']['default_platform'];
        $c = isset($_REQUEST['c'])?$_REQUEST['c']:$GLOBALS['config']['app']['default_controller'];
        $a = isset($_REQUEST['a'])?$_REQUEST['a']:$GLOBALS['config']['app']['default_action'];

        define('PLATFORM_NAME',$p);
        define('CONTROLLER_NAME',$c);
        define('ACTION_NAME',$a);
        define('__URL__',CONTROLLER_PATH.PLATFORM_NAME.DS);   //当前控制器目录
        define('__VIEW__',VIEW_PATH.PLATFORM_NAME.DS);        //当前平台的目录
    }
    /**
     * 自定义自动加载类
     * @Method autoLoad
     * @param $class_name
     * @email zonghua.hu@weicheche.cn
     * @author Hans
     * @date 2019/1/8
     * @time 16:35
     */
    private static function autoLoad($class_name)
    {
        $class_map = array(
            'MySQLDB'    => CORE_PATH.'DBMysql.class.php',
            'Model'      => CORE_PATH.'Model.class.php',
            'controller' => CORE_PATH.'Controller.class.php',
        );
        if (isset($class_map[$class_name])) {
            require  $class_map[$class_name];
        } elseif (substr($class_name,-5) == 'Model') {
            require MODEL_PATH.$class_name.'.class.php';
        } elseif(substr($class_name,-10) == 'Controller') {
            require  __URL__.$class_name.'.class.php';
        }
    }
    /**
     * 注册自定义加载类
     * @Method initRegisterAutoLoad
     * @email zonghua.hu@weicheche.cn
     * @author Hans
     * @date 2019/1/8
     * @time 16:36
     */
    private static function initRegisterAutoLoad()
    {

        spl_autoload_register('self::autoLoad');
    }
    /**
     * 请求分发
     * @Method initDispatch
     * @email zonghua.hu@weicheche.cn
     * @author Hans
     * @date 2019/1/8
     * @time 16:40
     */
    private static function initDispatch()
    {
        $controller_name = CONTROLLER_NAME.'Controller';
        $action_name = ACTION_NAME.'Action';
        $controller = new $controller_name();
        $controller->$action_name();
    }























}



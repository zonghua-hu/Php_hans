<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/5
 * Time: 12:04
 */
class DBMysql
{
    private $host;          //主机名
    private $port;          //端口
    private $user;          //用户名
    private $pwd;           //密码
    private $charset;       //字符编码
    private $dbname;        //数据库名字
    private $link;          //连接对象
    private static $instance;//保存mysql实例
    /**
     * 初始化连接数据库参数
     * @Method initParams
     * @param $config
     * @email zonghua.hu@weicheche.cn
     * @author Hans
     * @date 2019/1/5
     * @time 14:22
     */
    private function initParams($config)
    {
        $this->host = isset($config['host'])?$config['host']:'192.168.85.135';
        $this->port = isset($config['port'])?$config['port']:'3306';
        $this->user = isset($config['user'])?$config['user']:'weicheche';
        $this->pwd  = isset($config['pwd'])?$config['pwd']:'weicheche';
        $this->charset = isset($config['charset'])?$config['charset']:'utf8';
        $this->dbname = isset($config['dbname'])?$config['dbname']:'weicheche';
    }
    /**
     * 连接数据库
     * @Method connect
     * @email zonghua.hu@weicheche.cn
     * @author Hans
     * @date 2019/1/5
     * @time 14:27
     */
    private function connect()
    {
        $this->link = @mysqli_connect("{$this->host}:{$this->port}",$this->user,$this->pwd) or die("数据库连接失败~");
    }
    /**
     * 设置字符编码
     * @Method setCharset
     * @email zonghua.hu@weicheche.cn
     * @author Hans
     * @date 2019/1/5
     * @time 14:28
     */
    private function setCharset()
    {
        $sql = "set names '{$this->charset}'";
        $this->query($sql);
    }
    /**
     * 选择数据库
     * @Method selectDb
     * @email zonghua.hu@weicheche.cn
     * @author Hans
     * @date 2019/1/5
     * @time 14:30
     */
    private function selectDb()
    {
        $sql = "use '{$this->dbname}'";
        $this->query($sql);
    }
    /**
     * 执行sql语句的方法
     * @Method query
     * @param $sql
     * @return bool|mysqli_result
     * @email zonghua.hu@weicheche.cn
     * @author Hans
     * @date 2019/1/5
     * @time 14:35
     */
    public function query($sql)
    {
        if (!$result = mysqli_query($sql,$this->link)) {
            echo 'SQL语句执行失败~';
            echo '错误编号:'.mysqli_error(),'<br>';
            echo '错误信息:'.mysqli_error(),'<br>';
            echo '错误sql：'.$sql,'<br>';
            exit;
        }
        return $result;
    }
    /**
     * 构造函数
     * DB constructor.
     * @param $config
     */
    private function __construct($config)
    {
        $this->initParams($config);
        $this->connect();
        $this->setCharset();
        $this->selectDb();
    }
    private function __clone()
    {
        // TODO: Implement __clone() method.
    }
    /**
     * 公有的静态方法用来获取MySQLDB类的实例
     * @Method getInstance
     * @param array $config
     * @return DBMysql
     * @email zonghua.hu@weicheche.cn
     * @author Hans
     * @date 2019/1/8
     * @time 15:46
     */
    public static function getInstance($config = array())
    {
        if (!self::$instance instanceof self)
            self::$instance = new self($config);
        return self::$instance;
    }
    /**
     * 获取全部数据
     * @Method fetchAll
     * @param $sql
     * @param string $sql_type
     * @return array
     * @email zonghua.hu@weicheche.cn
     * @author Hans
     * @date 2019/1/5
     * @time 14:55
     */
    private function fetchAll($sql,$sql_type = 'assoc')
    {
        $rs = $this->query($sql);
        $sql_types = array(
            'assoc',
            'row',
            'array'
        );
        if (!in_array($sql_type,$sql_types)) {
            $sql_type = 'assoc';
        }
        $fetch_fun = 'mysql_fetch_'.$sql_type;
        $array = array();
        while ($rows = $fetch_fun($rs)) {
            $array[] = $rows;
        }
        return $array;
    }
    /**
     * 获取第一条记录
     * @Method fetchRow
     * @param $sql
     * @param string $fetch_type
     * @return array|null
     * @email zonghua.hu@weicheche.cn
     * @author Hans
     * @date 2019/1/8
     * @time 15:30
     */
    public function fetchRow($sql,$fetch_type = 'assoc')
    {
        $rs = $this->fetchAll($sql,$fetch_type);
        return empty($rs)?null:$rs[0];
    }
    /**
     * 获取第一行第一列的数据
     * @Method fetchColumn
     * @param $sql
     * @return mixed|null
     * @email zonghua.hu@weicheche.cn
     * @author Hans
     * @date 2019/1/8
     * @time 15:32
     */
    public function fetchColumn($sql)
    {
        $rs = $this->fetchRow($sql,'row');
        return empty($rs)?null:$rs[0];
    }
}

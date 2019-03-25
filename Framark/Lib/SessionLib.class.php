<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/14
 * Time: 11:52
 */
class SessionLib
{
    private $db;   //用来保存数据库实例
    public function __construct()
    {
        //自定义存储
        session_set_save_handler(
            array($this,'open'),
            array($this,'close'),
            array($this,'read'),
            array($this,'write'),
            array($this,'destory'),
            array($this,'gc')
        );
    }
    /**
     * 打开会话
     * @Method open
     * @email zonghua.hu@weicheche.cn
     * @author Hans
     * @date 2019/1/14
     * @time 12:04
     */
    public function open()
    {
        $this->db = DBMysql::getInstance($GLOBALS['config']['database']);
    }
    /**
     * 关闭会话
     * @Method close
     * @return bool
     * @email zonghua.hu@weicheche.cn
     * @author Hans
     * @date 2019/1/14
     * @time 14:31
     */
    public function close()
    {
        return true;
    }
    /**
     * 读取session
     * @Method read
     * @param $id
     * @return mixed
     * @email zonghua.hu@weicheche.cn
     * @author Hans
     * @date 2019/1/14
     * @time 14:41
     */
    public function read($id)
    {
        $sql = "select stname from wei_stations where id = {$id}";
        return $this->db->fetchColumn($sql);
    }
    /**
     * 写session
     * @Method write
     * @param $id
     * @param $name
     * @return mixed
     * @email zonghua.hu@weicheche.cn
     * @author Hans
     * @date 2019/1/14
     * @time 14:51
     */
    public function write($id,$name)
    {
        $time = time();
        $sql = "insert into wei_stations values ('$id','$name',$time) on duplicate key  update name={$name}";
        return $this->db->query($sql);

    }
    public function destory($id)
    {
        $sql = "delete from wei_stations where id = {$id}";
        return $this->db->query($sql);
    }
    /**
     * 垃圾回收，所有的过期会话
     * @return mixed
     */
    public function gc($maxlefetime)
    {
        $time = time()-$maxlefetime;
        $sql = "delete from wei_stations where updatetime<{$time}";
        return $this->db->query($sql);
    }
}
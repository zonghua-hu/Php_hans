<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/10
 * Time: 11:41
 */

class ProductsModel extends Model
{
    /**
     * 获取wei_admin表中所有的数据
     */
    public function getList()
    {
        return $this->db->fetchAll("select * from wei_Admin");
    }
    /**
     * 根据id删除相应的user
     */
    public function delUser($id)
    {
        $sql = "update wei_admin  set is_delete=1 where id =$id";
        return $this->db->query($sql);
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/24
 * Time: 11:02
 */

class UserClient
{
    public function main($is_out)
    {
        switch ($is_out) {
            case 0:
                $this->getUserInfoAll();
                break;
            case 1:
                $this->getOutUserInfoAll();
                break;
            default:
                continue;
        }
    }
    /**
     * 获取本公司员工信息方法
     */
    private function getUserInfoAll()
    {
        $new_staff = new UserInfo();
        $new_staff->getUserName("张町然");
        $new_staff->getHomeAddress("愠都");
        $new_staff->getHomeTelNumber("111");
        $new_staff->getJobPosition("帝女");
        $new_staff->getMobileNumber("9999");
        $new_staff->getTellNumber("029-888");
    }
    /**
     * 获取外部员工信息方法
     */
    private function getOutUserInfoAll()
    {
        $out_staff = new OuterUserInfo();
        $out_staff->getUserName();
        $out_staff->getTellNumber();
        $out_staff->getMobileNumber();
        $out_staff->getJobPosition();
        $out_staff->getHomeTelNumber();
        $out_staff->getHomeAddress();
    }

}
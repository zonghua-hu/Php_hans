<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/24
 * Time: 10:24
 */

interface  IUserInfo
{
    public function getUserName($name);

    public function getHomeAddress($address);

    public function getMobileNumber($mobiles);

    public function getTellNumber($phones);

    public function getJobPosition($jobs);

    public function getHomeTelNumber($home_number);

}
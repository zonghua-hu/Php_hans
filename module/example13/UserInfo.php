<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/24
 * Time: 10:26
 */

class UserInfo implements IUserInfo
{
    public function getUserName($names)
    {
        // TODO: Implement getUserName() method.
        return "this emplyer name is ".$names;
    }

    public function getHomeTelNumber($home_number)
    {
        // TODO: Implement getHomeTelNumber() method.
        return "this staff home number is ".$home_number;
    }

    public function getHomeAddress($address)
    {
        // TODO: Implement HomeAddress() method.
        return "this staff home address is ".$address;
    }

    public function getJobPosition($jobs)
    {
        // TODO: Implement JobPosition() method.
        return "this staff job is".$jobs;
    }

    public function getMobileNumber($mobile)
    {
        return "this staff mobile number is ".$mobile;
    }

    public function getTellNumber($phones)
    {
        // TODO: Implement TellNumber() method.
        return "this staff tell number is ".$phones;
    }

}
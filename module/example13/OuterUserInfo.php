<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/24
 * Time: 10:46
 */

class OuterUserInfo extends OuterUser implements IOutUser
{
    private $baseinfo   = [];
    private $homeinfo   = [];
    private $officeinfo = [];

    private $home_address;
    private $home_phone;
    private $job_position;
    private $mobile_number;
    private $office_number;
    private $staff_name;

    public function __construct()
    {
        $this->baseinfo = parent::getUserBaseInfo();

        $this->homeinfo = parent::getUserHomeInfo();

        $this->officeinfo = parent::getUserOfficeInfo();
    }
    /**
     * 获取办公电话号码
     * @return mixed
     */
    public function getMobileNumber()
    {
        $this->office_number = $this->officeinfo['officeNumber'];
        return $this->office_number;
    }
    /**
     * 获取工作职位
     * @return mixed
     */
    public function getJobPosition()
    {
        $this->job_position = $this->officeinfo['jobPosition'];
        return $this->job_position;
    }
    /**
     * 获取个人电话
     * @return mixed
     */
    public function getTellNumber()
    {
        $this->mobile_number = $this->baseinfo['mobileNumber'];
        return $this->mobile_number;
    }
    /**
     * 获取家庭地址
     * @return mixed
     */
    public function getHomeAddress()
    {
        $this->home_address = $this->homeinfo['homeAddress'];
        return $this->home_address;
    }
    /**
     * 获取家庭电话
     * @return mixed
     */
    public function getHomeTelNumber()
    {
        $this->home_phone = $this->homeinfo['homeNumber'];
        return $this->home_phone;
    }
    /**
     * 获取个人姓名
     * @return mixed
     */
    public function getUserName()
    {
        $this->staff_name = $this->baseinfo['userName'];
        return $this->staff_name;
    }






























}
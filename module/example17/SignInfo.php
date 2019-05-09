
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/9
 * Time: 10:39
 */

class SignInfo
{
    private $id;
    private $location;
    private $subject;
    private $post_address;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setLocation($location)
    {
        $this->location = $location;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function getPostAddress()
    {
        return $this->post_address;
    }

    public function setPostAddress($address)
    {
        $this->post_address = $address;
    }

}
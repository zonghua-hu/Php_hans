<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/12
 * Time: 17:48
 */

class Mail
{
    private $receiver;
    private $subject;
    private $application;
    private $context;
    private $tail;
    private $adv_obj;

    public function __construct()
    {
        $this->adv_obj = new AdvTemplate();
        $this->subject = $this->adv_obj->getAdvSubject();
        $this->context = $this->adv_obj->getAdvContext();
    }

    public function getReceiver()
    {
        return $this->receiver;
    }

    public function setReceiver($receivers)
    {
        $this->receiver = $receivers;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject($sub)
    {
        $this->subject = $sub;
    }

    public function getApplication()
    {
        return $this->application;
    }

    public function setApplication($applications)
    {
        $this->application = $applications;
    }

    public function getContext()
    {
        return $this->context;
    }

    public function setContext($con)
    {
        $this->context = $con;
    }

    public function getTail()
    {
        return $this->tail;
    }

    public function setTail($tails)
    {
        $this->tail = $tails;
    }

}
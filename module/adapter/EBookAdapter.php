<?php

/**
 * 电子书接口适配器
 * Class EBookAdapter
 */
class EBookAdapter implements BookInterface
{
    protected $ebook;

    public function __construct(EBookInterface $ebook)
    {
        $this->ebook = $ebook;
    }
    public function truePage()
    {
        return $this->ebook->pressNest();
    }
    public function getPage()
    {
        $this->ebook->getPage()[0];
    }
    public function open()
    {
        return $this->ebook->unlock();
    }

}
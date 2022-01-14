<?php


class Kindle implements EBookInterface
{
    private $page = 1;
    private $pageSize = 100;

    public function getPage()
    {
        return[$this->page, $this->pageSize];
    }

    public function pressNest()
    {
        $this->page++;
    }

    public function unlock()
    {
        $this->page = 0;
        $this->pageSize = 0;
        return true;
    }

}
<?php


class Paper implements IPaperInterface
{
    private $page = 99;

    private $totalPage = 9999;

    public function getPage()
    {
        return $this->page;
    }
    public function open()
    {
        return $this->totalPage;
    }
    public function nextPage()
    {
        $this->page++;
    }


}
<?php

/**
 * 纸质书适配器
 * Class BookAdapter
 */
class BookAdapter implements BookInterface
{
    /**
     *
     * @var IPaperInterface
     */
    protected $paper;

    public function __construct(IPaperInterface $paper)
    {
        $this->paper = $paper;
    }
    public function open()
    {
        return $this->paper->open();
    }
    public function getPage()
    {
        return $this->paper->getPage();
    }
    public function truePage()
    {
        return $this->paper->nextPage();
    }
}

<?php


trait peoples
{
    private $name = 'Tom';
    private $age = 20;
    private $work = 'students';

    public function run()
    {
        return 'The'.$this->age.'years yong boys'.$this->name.'running to school,because he is a '.$this->work;
    }
}

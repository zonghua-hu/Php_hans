<?php

namespace FacadeTwo;

class PersonTwo
{
    private $name;
    private $age;
    private $employ;

    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setAge(int $age)
    {
        $this->age = $age;
        return $this;
    }

    public function getAge()
    {
        return $this->age;
    }

    public function setEmploy(string $employ)
    {
        $this->employ = $employ;
        return $this;
    }

    public function format()
    {
        return "person name:" . $this->name . "|年龄:" . $this->age . "|职业:" . $this->employ . PHP_EOL;
    }
}

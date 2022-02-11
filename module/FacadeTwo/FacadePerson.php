<?php

namespace FacadeTwo;

/**
 * @method  string getName()
 * @method  setName(string $name)
 * @method  int getAge()
 * @method  setAge(int $age)
 * @method  string getEmploy()
 * @method  setEmploy(string $employ)
 * @method  string format()
 * Class FacadePerson
 * @package FacadeTwo
 * @see PersonTwo
 */
class FacadePerson
{
//    public static function __callStatic($name, $arguments)
//    {
//        $person = new PersonTwo();
//        if (count($arguments) == 1) {
//            $arguments = current($arguments);
//        }
//        return $person->{$name}($arguments);
//    }
    public function __call($name, $arguments)
    {
        $person = new PersonTwo();
        return $person->{$name}(...$arguments);
    }
}

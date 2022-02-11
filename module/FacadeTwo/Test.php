<?php

require_once 'PersonTwo.php';
require_once 'FacadePerson.php';

echo (new \FacadeTwo\FacadePerson())->setName("hans")->setAge(30)->setEmploy("IT Hans")->format();

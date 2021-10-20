<?php

require_once 'Container.php';
require_once 'Father.php';
require_once 'SingleContainer.php';
require_once 'Person.php';
require_once 'Son.php';
require_once '../../vendor/wazsmwazsm/ioc-container/src/IOC/Container.php';

//\Container\Container::getInstance(\Container\Person::class)->say();

//(new \Container\Person(new \Container\Son()))->say();

//\Container\Container::getInstance(\Container\Father::class)->say();

\IOC\Container::getInstance(\Container\Person::class)->say();


//$s = \Container\SingleContainer::run(\Container\Person::class, 'say');



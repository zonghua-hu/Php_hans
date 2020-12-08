<?php

require "../../vendor/autoload.php";

use Pheanstalk\Pheanstalk;

$beanstalkObj = Pheanstalk::create('127.0.0.1', 11300);
print_r($beanstalkObj->stats());

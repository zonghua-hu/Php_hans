<?php

require_once 'FormatterInterface.php';
require_once 'HelloService.php';
require_once 'HttpFormat.php';
require_once 'PlainFormat.php';
require_once 'Service.php';

$service = new \bridge\HelloService(new \bridge\HttpFormat());
$str = $service->getFormatter("hello world http");
print_r($str);
die;

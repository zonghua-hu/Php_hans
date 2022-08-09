<?php

require_once 'Record.php';
require_once 'RecordProxy.php';

$data = [];
$proxy = new \proxy\RecordProxy($data);

$proxy->xyt = false;

var_dump($proxy->xyt === false);

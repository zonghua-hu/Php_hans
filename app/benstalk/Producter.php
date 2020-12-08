<?php

require "../../vendor/autoload.php";

use Pheanstalk\Pheanstalk;

$ph = Pheanstalk::create('127.0.0.1', 11300);

for ($i=0; $i<50; $i++) {
    $data = [
        'key'=> 'testKey'.$i,
        'value' => 'hans',
        'time' => time()
    ];
    $result = $ph->useTube('hans-test');
    $result = $ph->put(json_encode($data), Pheanstalk::DEFAULT_PRIORITY);
    var_dump($result);
}

<?php

set_time_limit(0);
ini_set('default_socket_timeout', 900);

require "../../vendor/autoload.php";

use Pheanstalk\Pheanstalk;

$ph = Pheanstalk::create('127.0.0.1', 11300);

while (true) {
    $job = $ph->watch('hans-test')
        ->ignore('default')
        ->reserve();

    if ($job) {
        sleep(2);
        echo $job->getData();
        echo PHP_EOL;
        $ph->delete($job);
    }
}

<?php

$account = new \Responsibility\AccountMoneyHandler();
$account
    ->setNext(new \Responsibility\DiscountHandler())
    ->setNext(new \Responsibility\SensitiveWordsHandler());
$account->handle(new \http\Client\Request());

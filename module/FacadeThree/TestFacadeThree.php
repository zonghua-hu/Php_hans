<?php

require_once 'ComputerFacade.php';
require_once 'FridgeFacade.php';
require_once 'TelevisionFacade.php';
require_once 'ElectricBrakeFacade.php';

$face = new \FacadeThree\ElectricBrakeFacade();
$face->turnOff();

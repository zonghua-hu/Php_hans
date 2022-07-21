<?php

namespace FacadeThree;

/**
 * 门面
 * Class ElectricBrakeFacade
 * @package FacadeThree
 */
class ElectricBrakeFacade
{
    private $fridge;
    private $television;
    private $computer;

    /**
     * ElectricBrakeFacade constructor.
     */
    public function __construct()
    {
        $this->computer = new ComputerFacade();
        $this->fridge = new FridgeFacade();
        $this->television = new TelevisionFacade();
    }

    /**
     * @return bool
     */
    public function turnOff(): bool
    {
        $this->television->turnOff();
        $this->fridge->turnOff();
        $this->computer->turnOff();
        return true;
    }
}

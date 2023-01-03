<?php

namespace model\Builder;

class AssemblerBuilder implements ComputerInterface
{
    /**
     * @var Computer
     */
    private Computer $computer;

    /**
     * AssemblerBuilder constructor.
     */
    public function __construct()
    {
        $this->computer = new Computer();
    }

    /**
     * @return Computer
     */
    public function createComputer(): Computer
    {
        return $this->computer;
    }

    /**
     * @param string $cpu
     */
    public function createCpu(string $cpu): void
    {
        $this->computer->setCpu($cpu);
    }

    /**
     * @param string $hardDisk
     */
    public function createHardDisk(string $hardDisk): void
    {
        $this->computer->setHardDisk($hardDisk);
    }

    /**
     * @param string $mainBoard
     */
    public function createMainBoard(string $mainBoard): void
    {
        $this->computer->setMainBoard($mainBoard);
    }

    /**
     * @param string $memory
     */
    public function createMemory(string $memory): void
    {
        $this->computer->setMemory($memory);
    }
}

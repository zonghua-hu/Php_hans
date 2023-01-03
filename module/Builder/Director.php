<?php

namespace model\Builder;

class Director
{
    /**
     * @var ComputerInterface
     */
    private ComputerInterface $computer;

    /**
     * Director constructor.
     * @param ComputerInterface $computer
     */
    public function __construct(ComputerInterface $computer)
    {
        $this->computer = $computer;
    }

    /**
     * @param string $cpu
     * @param string $memory
     * @param string $mainBoard
     * @param string $hardDisk
     * @return Computer
     */
    public function createComputer(string $cpu, string $memory, string $mainBoard, string $hardDisk): Computer
    {
        $this->computer->createCpu($cpu);
        $this->computer->createHardDisk($hardDisk);
        $this->computer->createMainBoard($mainBoard);
        $this->computer->createMemory($memory);
        return $this->computer->createComputer();
    }
}

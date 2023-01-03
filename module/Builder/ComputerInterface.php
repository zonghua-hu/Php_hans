<?php

namespace model\Builder;

interface ComputerInterface
{
    /**
     * @param string $cpu
     */
    public function createCpu(string $cpu): void;

    /**
     * @param string $hardDisk
     */
    public function createHardDisk(string $hardDisk): void;

    /**
     * @param string $mainBoard
     */
    public function createMainBoard(string $mainBoard): void;

    /**
     * @param string $memory
     */
    public function createMemory(string $memory): void;

    /**
     * @return mixed
     */
    public function createComputer(): Computer;
}

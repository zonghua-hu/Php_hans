<?php

namespace model\Builder;

class Computer
{
    /**
     * @var string
     */
    private string $cpu;

    /**
     * @var string
     */
    private string $hardDisk;

    /**
     * @var string
     */
    private string $mainBoard;

    /**
     * @var string
     */
    private string $memory;

    /**
     * @return string
     */
    public function getCpu(): string
    {
        return $this->cpu;
    }

    /**
     * @param string $cpu
     */
    public function setCpu(string $cpu): void
    {
        $this->cpu = $cpu;
    }

    /**
     * @return string
     */
    public function getHardDisk(): string
    {
        return $this->hardDisk;
    }

    /**
     * @param string $hardDisk
     */
    public function setHardDisk(string $hardDisk): void
    {
        $this->hardDisk = $hardDisk;
    }

    /**
     * @return string
     */
    public function getMainBoard(): string
    {
        return $this->mainBoard;
    }

    /**
     * @param string $mainBoard
     */
    public function setMainBoard(string $mainBoard): void
    {
        $this->mainBoard = $mainBoard;
    }

    /**
     * @return string
     */
    public function getMemory(): string
    {
        return $this->memory;
    }

    /**
     * @param string $memory
     */
    public function setMemory(string $memory): void
    {
        $this->memory = $memory;
    }
}
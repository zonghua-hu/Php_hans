<?php
namespace ObjectPool;

use Countable;

class WorkPool implements Countable
{
    private $occupiedWorkers = [];

    private $freeWorkers = [];

    public function get()
    {
        if (count($this->freeWorkers) == 0) {
            $worker = new StringReverseWorker();
        } else {
            $worker = array_pop($this->freeWorkers);
        }
        $this->occupiedWorkers[spl_object_hash($worker)] = $worker;
        return $worker;
    }

    public function dispose(StringReverseWorker $reverseWorker)
    {
        $key = spl_object_hash($reverseWorker);

        if (isset($this->occupiedWorkers[$key])) {
            unset($this->occupiedWorkers[$key]);
            $this->freeWorkers[$key] = $reverseWorker;
        }
    }

    public function count()
    {
        return count($this->occupiedWorkers) + count($this->freeWorkers);
    }
}

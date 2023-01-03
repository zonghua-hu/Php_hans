<?php

namespace model\Builder;

class Demo
{
    /**
     * @return Computer
     */
    public function main()
    {
        $assembler = new AssemblerBuilder();
        $director = new Director($assembler);
        $computer = $director->createComputer(
            "Intel 酷睿i9 7900X",
            "三星M9T 2TB （HN-M201RAD）",
            "技嘉AORUS Z270X-Gaming 7",
            "科赋Cras II 红灯 16GB DDR4 3000"
        );
        return $computer;
    }
}

<?php

namespace App\Command\Migration;

class RunCommandContext
{
    /**
     * @var int
     */
    private $versionNum;

    public function __construct($versionNum)
    {
        $this->versionNum = $versionNum;
    }

    /**
     * @return int
     */
    public function getVersionNum(): int
    {
        return $this->versionNum;
    }
}
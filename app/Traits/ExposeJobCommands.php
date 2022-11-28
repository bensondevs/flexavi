<?php

namespace App\Traits;

trait ExposeJobCommands
{
    public function getAllVars(): array
    {
        return get_object_vars($this);
    }
}

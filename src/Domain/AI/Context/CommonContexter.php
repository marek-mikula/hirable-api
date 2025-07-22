<?php

namespace Domain\AI\Context;

class CommonContexter
{
    public function getCommonContext(): string
    {
        $result = [];



        return implode(PHP_EOL, $result);
    }
}
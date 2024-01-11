<?php

namespace App\Twig;

use Twig\Extension\RuntimeExtensionInterface;

class AppZeroAdder implements RuntimeExtensionInterface
{
    public function normaling_time(string $time){
        return $this->zero_adding(intdiv(intval($time), 60), 2)  . ":" . $this->zero_adding(intval($time)%60, 2);
    }
    public function zero_adding(?string $config, int $count)
    {
        return str_pad($config, $count, '0', STR_PAD_LEFT);
    }

}
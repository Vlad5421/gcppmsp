<?php

namespace App\Twig;

use Twig\Extension\RuntimeExtensionInterface;

class AppTimeNormalizer implements RuntimeExtensionInterface
{
    public function timeNormalize($time): string
    {
        return str_pad(intdiv($time, 60), 2, "0", STR_PAD_LEFT). ":" .str_pad($time%60, 2, "0", STR_PAD_LEFT);
    }

}
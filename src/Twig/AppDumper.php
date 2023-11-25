<?php

namespace App\Twig;

use Twig\Extension\RuntimeExtensionInterface;

class AppDumper implements RuntimeExtensionInterface
{
    public function vdump($var)
    {
        dump($var);
    }

}
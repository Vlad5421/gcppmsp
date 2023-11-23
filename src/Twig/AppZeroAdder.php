<?php

namespace App\Twig;

use Symfony\Component\Asset\Packages;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Extension\RuntimeExtensionInterface;

class AppZeroAdder implements RuntimeExtensionInterface
{
    public function zero_adding(?string $config, int $count)
    {
        return str_pad($config, $count, '0', STR_PAD_LEFT);
    }

}
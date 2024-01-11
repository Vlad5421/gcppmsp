<?php

namespace App\Services;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CustomParametersBug
{
    private ParameterBagInterface $param_bag;

    public function __construct(ParameterBagInterface $param_bag)
    {
        $this->param_bag = $param_bag;
    }

    public function get(string $name) {
        return $this->param_bag->get($name);
    }
}

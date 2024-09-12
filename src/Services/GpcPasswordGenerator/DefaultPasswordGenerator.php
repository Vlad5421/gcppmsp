<?php

namespace App\Services\GpcPasswordGenerator;


class DefaultPasswordGenerator implements PasswordGeneratorInterface
{
    public function generatePass() : string
    {
        return "123456";
    }
}
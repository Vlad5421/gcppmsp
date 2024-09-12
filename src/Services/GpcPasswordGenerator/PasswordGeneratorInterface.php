<?php

namespace App\Services\GpcPasswordGenerator;

interface PasswordGeneratorInterface
{
    public function generatePass() : string;

}
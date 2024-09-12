<?php

namespace App\Services\GpcPasswordGenerator;


class SevenPasswordGenerator implements PasswordGeneratorInterface
{
    public function generatePass(int $length = 8, string $availableSets = 'luds') : string
    {
        $symbols = [];
        $password = '';
        $str = '';
        if (strpos($availableSets, 'l') !== false)
        {
            $symbols[] = 'abcdefghjkmnpqrstuvwxyz';
        }
        if (strpos($availableSets, 'u') !== false)
        {
            $symbols[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
        }
        if (strpos($availableSets, 'd') !== false)
        {
            $symbols[] = '23456789';
        }
        if (strpos($availableSets, 's') !== false)
        {
            $symbols[] = '!@$%&*?';
        }
        foreach ($symbols as $symbol)
        {
            $password .= $symbol[array_rand(str_split($symbol))];
            $str .= $symbol;
        }
        $str = str_split($str);
        for ($i = 0; $i < $length - count($symbols); $i++)
        {
            $password .= $str[array_rand($str)];
        }
        $password = str_shuffle($password);
        return $password;
    }
}
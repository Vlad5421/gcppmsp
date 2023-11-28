<?php

namespace App\Twig;

use App\Entity\Card;
use Twig\Extension\RuntimeExtensionInterface;

class AppCardDiv implements RuntimeExtensionInterface
{
    public function card_div($elem)
    {
        /** @var Card $elem */
        echo "<div style='border: #51585e solid 1px; padding: 10px; margin: 10px;'>";
        echo $elem->getService()->getName() ."<br>";
        echo $elem->getSpecialist()->getFIO() ."<br>";
        echo $elem->getFilial()->getAddress() ."<br>";

        echo (new AppTimeNormalizer())->timeNormalize($elem->getStart()) ."<br>";
        echo $elem->getDate()->format('d.m.Y') ."<br>";
//        dump($elem->getDate());
        echo "</div>";

    }
}
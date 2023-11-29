<?php

namespace App\Twig;

use App\Entity\Card;
use App\Repository\VisitorRepository;
use Twig\Extension\RuntimeExtensionInterface;

class AppCardDiv implements RuntimeExtensionInterface
{
    private VisitorRepository $vis_repo;

    public function __construct(VisitorRepository $vis_repo)
    {
        $this->vis_repo = $vis_repo;
    }

    public function card_div($elem)
    {
//        $visitor = $this->vis_repo->find()
        /** @var Card $elem */
        echo "<div style='border: #51585e solid 1px; padding: 10px; margin: 10px;'>";
        echo $elem->getService()->getName() ."<br>";
        echo $elem->getVisitors()[0]->getReason() ? $elem->getVisitors()[0]->getReason() : "причина не описана" ."<br>";
        echo $elem->getService()->getName() ."<br>";
        echo $elem->getSpecialist()->getFIO() ."<br>";
        echo $elem->getFilial()->getAddress() ."<br>";

        echo (new AppTimeNormalizer())->timeNormalize($elem->getStart()) ."<br>";
        echo $elem->getDate()->format('d.m.Y') ."<br>";
//        dump($elem->getDate());
        echo "</div>";

    }
}
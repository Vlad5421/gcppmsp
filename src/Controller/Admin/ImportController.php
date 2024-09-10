<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use App\Services\Admin\ScheduleImporter;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Services\GpcPasswordGenerator\PasswordGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ImportController extends AbstractController
{
    #[
        Route('/manage-panel/user/import', name: 'app_admin_user_import'),
        IsGranted('ROLE_SERVICE_ADMIN')
    ]
    public function import(ScheduleImporter $scheduleImporter) : Response
    {
        $scheduleImporter->readCsvs("/public/uploads/gallery/workers.csv", "/public/uploads/gallery/filials.csv");

        dd($scheduleImporter->import());
    }

    #[
        Route('/manage-panel/addroleall', name: 'app_admin_user_import'),
        IsGranted('ROLE_ADMIN')
    ]
    public function roleAdder(UserRepository $repo, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher, ) : Response
    {
        $count = 0;
        $alluser = 0;
        $users = $repo->findAll();
        foreach ($users as $user)
        {
            /** @var \App\Entity\User $user */
            if (! in_array("ROLE_ADMIN", $user->getRoles()) && ! in_array("ROLE_SERVICE_ADMIN", $user->getRoles()) && $user->getId() != "2")
            {
                $user->setPassword($passwordHasher->hashPassword($user, '123456'));
                $em->persist($user);
                $count++;
                dump($user->getFIO());
            }
            $alluser++;
            // break;
        }
        $em->flush();

        dd($count, $alluser);
    }

    #[Route("/passgenerat", name: "app_pass_generate")]
    public function passGen(PasswordGeneratorInterface $defoulPassGen)
    {
        dd($defoulPassGen->generatePass(10));
    }

}
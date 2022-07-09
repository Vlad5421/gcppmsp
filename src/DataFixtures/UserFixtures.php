<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends BaseFixtures
{
    private UserPasswordHasherInterface $passwordHasher;
    private $users = [
        ['name' => 'Пятый Проффисионал', 'email' => 'piatiy@mail.ru'],
        ['name' => 'Второй Спец', 'email' => 'vtoroy@mail.ru'],
        ['name' => 'Лутший Проффисионал', 'email' => 'best@mail.ru'],
        ['name' => 'Четвертый Проффисионал', 'email' => 'for@mail.ru'],
        ['name' => 'Третий Проффисионал', 'email' => 'tretiy@mail.ru'],

        ];

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {

        $this->passwordHasher = $passwordHasher;
    }

    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(User::class, 1, function (User $user) use ($manager) {
            $user
                ->setEmail('admin@gpc.ru')
                ->setFIO('Администратор')
                ->setPassword($this->passwordHasher->hashPassword($user, '123456'))
                ->setRoles(['ROLE_ADMIN'])
            ;
        });
        $i =1;
        foreach ($this->users as $usr){

            $this->create(User::class, function (User $user) use ($manager, $usr) {
                $user
                    ->setEmail($usr['email'])
                    ->setFIO($usr['name'])
                    ->setPassword($this->passwordHasher->hashPassword($user,'123456'))
                ;

            }, $i);
            $i += 1;
        }
        $manager->flush();

    }
//    public function getDependencies()
//    {
//        return [
//            ComplectFixtures::class,
//        ];
//    }
}

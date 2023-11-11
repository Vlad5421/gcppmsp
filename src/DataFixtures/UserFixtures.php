<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends BaseFixtures
{
    private UserPasswordHasherInterface $passwordHasher;
    private $users = [
        ['name' => 'Пятый Проффисионал', 'email' => 'vladislav_ts@bk.ru'],
        ['name' => 'Второй Спец', 'email' => 'vladislav_ts@bk.ru'],
        ['name' => 'Лутший Проффисионал', 'email' => 'vladislav_ts@bk.ru'],
        ['name' => 'Четвертый Проффисионал', 'email' => 'vladislav_ts@bk.ru'],
        ['name' => 'Третий Проффисионал', 'email' => 'vladislav_ts@bk.ru'],

        ];

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {

        $this->passwordHasher = $passwordHasher;
    }

    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(User::class, 1, function (User $user) use ($manager) {
            $user
                ->setEmail('vladislav_ts@bk.ru')
                ->setFIO('Администратор')
                ->setPassword($this->passwordHasher->hashPassword($user, '123456'))
                ->setRoles(['ROLE_ADMIN'])
            ;
        });

    }
//    public function getDependencies()
//    {
//        return [
//            ComplectFixtures::class,
//        ];
//    }
}

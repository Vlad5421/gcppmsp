<?php

namespace App\Commands;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-random-worker',
    description: 'Create random worker.',
    aliases: ['app:crerawo'],
    hidden: false
)]
class CreateRandomWorker extends Command
{

    private UserPasswordHasherInterface $pas_hasher;
    private UserRepository $om;
    // private VisitorRepository $visit_repo;

    public function __construct(UserPasswordHasherInterface $pas_hasher, UserRepository $om)
    {
        parent::__construct();
        $this->pas_hasher = $pas_hasher;
        $this->om = $om;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $user = new User();
        $user
            ->setEmail('random1@gpc.gpc')
            ->setFIO('Рандом Один')
            ->setPassword($this->pas_hasher->hashPassword($user, '123456'))
            ->setRoles(['ROLE_WORKER'])
        ;
        $this->om->add($user);



        $output->writeln("Создан пользователь: Рандом Один");

        return Command::SUCCESS;
    }
}
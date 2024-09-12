<?php

namespace App\Commands;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Services\GpcPasswordGenerator\PasswordGeneratorInterface;
use App\Services\MailService;
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
    private PasswordGeneratorInterface $gen_pass;
    private MailService $mailer;
    // private VisitorRepository $visit_repo;

    public function __construct(UserPasswordHasherInterface $pas_hasher, UserRepository $om, PasswordGeneratorInterface $gen_pass, MailService $mailer)
    {
        parent::__construct();
        $this->pas_hasher = $pas_hasher;
        $this->om = $om;
        $this->gen_pass = $gen_pass;
        $this->mailer = $mailer;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $new_pass = $this->gen_pass->generatePass(10);
        $new_mail = "random2@gpc.gpc";

        $user = new User();
        $user
            ->setEmail($new_mail)
            ->setFIO('Рандом Два')
            ->setPassword($this->pas_hasher->hashPassword($user, $new_pass))
            ->setRoles(['ROLE_WORKER'])
        ;
        $this->om->add($user);

        $this->mailer->sendMail("Мегаадмин", $new_mail, "Вам установлен пароль: $new_pass", true);


        $output->writeln("Создан пользователь: Рандом Два");

        return Command::SUCCESS;
    }
}
<?php

namespace App\Commands;

use App\Repository\UserRepository;
use App\Services\GpcPasswordGenerator\PasswordGeneratorInterface;
use App\Services\MailService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:set-password',
    description: 'Set password one user.',
    aliases: ['app:passwd'],
    hidden: false
)]
class SetPassword extends Command
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

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {

        $user = $this->om->findOneBy(["email" => $input->getArgument("email")]);
        // dump($user);
        // die;

        $pass_length = 8;
        $dev = $input->getArgument("dev") == "dev";

        if ($input->getArgument("pass_length"))
        {
            $pass_lengh = intval($input->getArgument("pass_length"));
        }

        $new_pass = $this->gen_pass->generatePass($pass_length);
        $user->setPassword($this->pas_hasher->hashPassword($user, $new_pass));
        $newed_user = [$user->getFIO(), $user->getEmail(), $new_pass];

        if (! $dev)
        {
            $output->writeln("Отправляем письмо");

            $mail_to_send = $user->getEmail();
            $this->mailer->sendMail("CRM Гцппмсп г.Омска", $mail_to_send, "Ваш логин: " . $newed_user[1] . ". Вам установлен пароль: $new_pass");
        } else
        {
            $output->writeln($newed_user[0] . ' ' . $newed_user[1] . ' ' . $newed_user[2]);
        }

        $output->writeln("Сохраняем в БД");
        // записать изменения в БД
        $this->om->add($user);



        return Command::SUCCESS;
    }
    protected function configure() : void
    {
        $this
            // ...
            // ->addArgument('login', InputArgument::REQUIRED, 'Who do you want to greet?')
            ->addArgument('email', InputArgument::REQUIRED, 'Email of user?')
            ->addArgument('dev', InputArgument::OPTIONAL, 'It\'s dev?')
            ->addArgument('pass_length', InputArgument::OPTIONAL, 'Length of new password')
        ;
    }
}
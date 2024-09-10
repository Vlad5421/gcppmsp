<?php

namespace App\Commands;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Services\GpcPasswordGenerator\PasswordGeneratorInterface;
use App\Services\MailService;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:upade-all-passwords',
    description: 'Upade All Passwords.',
    aliases: ['app:uppallpass'],
    hidden: false
)]
class UpadeAllPasswords extends Command
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

        $users = $this->om->findAll();
        $output->writeln($input->getArgument("pass_length"));
        $output->writeln($input->getArgument("dev"));
        // die;
        $output->writeln("Всего пользователей: " . count($users));
        $pass_lengh = 8;
        $number = 1;
        $dev = $input->getArgument("dev") == "dev";

        if ($input->getArgument("pass_length"))
        {
            $pass_lengh = intval($input->getArgument("pass_length"));
        }
        $array_datas = [];

        foreach ($users as $user)
        {


            if (! in_array("ROLE_SERVICE_ADMIN", $user->getRoles()) && ! in_array("ROLE_ADMIN", $user->getRoles()))
            {
                $new_pass = $this->gen_pass->generatePass($pass_lengh);
                $user
                    ->setPassword($this->pas_hasher->hashPassword($user, $new_pass))
                    ->setRoles(['ROLE_WORKER'])
                ;

                if ($dev)
                {
                    $new_mail = "worker_" . $number++ . "@gpc.gpc";
                    // сменить логин
                    $user->setEmail($new_mail);
                }
                $mail_to_send = $user->getEmail();
                $newed_user = [$user->getFIO(), $user->getEmail(), $new_pass];
                $array_datas[] = $newed_user;

                // записать изменения в БД
                $this->om->add($user);
                $output->writeln($newed_user[0] . ' ' . $newed_user[1] . ' ' . $newed_user[2]);
                // Если не дев - отправить письмо
                if (! $dev)
                {
                    $this->mailer->sendMail("CRM Гцппмсп г.Омска", $mail_to_send, "Ваш логин: " . $newed_user[1] . ". Вам установлен пароль: $new_pass");
                    // $output->writeln("Типа отправили письмо");
                }
            }

        }

        $this->mailer->sendMail("CRM Гцппмсп г.Омска", $mail_to_send, json_encode($array_datas, true), true);



        return Command::SUCCESS;
    }
    protected function configure() : void
    {
        $this
            // ...
            // ->addArgument('login', InputArgument::REQUIRED, 'Who do you want to greet?')
            ->addArgument('pass_length', InputArgument::OPTIONAL, 'Your last name?')
            ->addArgument('dev', InputArgument::OPTIONAL, 'Your last name?')
        ;
    }
}
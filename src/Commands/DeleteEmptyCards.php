<?php

namespace App\Commands;

use App\Repository\VisitorRepository;
use App\Repository\CardRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:del-empty-card',
    description: 'Delete empty cards.',
    aliases: ['app:delemca'],
    hidden: false
)]
class DeleteEmptyCards extends Command
{

    private CardRepository $card_repo;
    private VisitorRepository $visit_repo;

    public function __construct(CardRepository $card_repo, VisitorRepository $visit_repo)
    {
        parent::__construct();
        $this->card_repo = $card_repo;
        $this->visit_repo = $visit_repo;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $count = 0;
        $cards = $this->card_repo->findEmpty(new \DateTime("-10 min"));
        foreach ($cards as $card) {
            $visitor = $this->visit_repo->findBy(["card" => $card]);
            if (!count($visitor) > 0) {
                $this->card_repo->remove($card);
                $count += 1;
            }
        }
        $output->writeln("Удалено $count записей");

        return Command::SUCCESS;
    }
}

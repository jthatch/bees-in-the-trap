<?php

declare(strict_types=1);

namespace BeesInTheTrap\Command;

use BeesInTheTrap\Game;
use BeesInTheTrap\GameFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class PlayCommand.
 */
class GamePlayCommand extends Command
{
    private Game $game;

    public function __construct()
    {
        parent::__construct('bees:play');
        $this->game = GameFactory::create();
    }

    /**
     * Configure our command.
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Play Bees In The Trap')
            ->addOption(
                'auto',
                'a',
                InputOption::VALUE_NONE,
                'Run in auto mode, doesn\'t require user input',
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io       = new SymfonyStyle($input, $output);
        $question = new Question('Type <info>"hit"</info> to attack the trap:');

        do {
            if (!$input->getOption('auto') || !$input->isInteractive()) {
                do {
                    $userInput = $io->askQuestion($question);
                } while ('hit' !== $userInput);
            }

            $turn = $this->game->takeTurn();
            $io->writeln($turn);
        } while (!$this->game->isGameOver());

        $io->writeln(sprintf('Congratulations, Game Over! After %d hits you destroyed the trap!', $this->game->getHits()));

        return 0;
    }
}

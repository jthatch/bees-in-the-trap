<?php

declare(strict_types=1);

namespace BeesInTheTrap\Console;

use BeesInTheTrap\Console\Display\GamePlay;
use BeesInTheTrap\Console\Display\WelcomeMessage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PlayCommand.
 */
class PlayCommand extends Command
{
    /** @var WelcomeMessage */
    private $welcomeMessage;

    /** @var GamePlay */
    private $gamePlay;

    public function __construct(
        WelcomeMessage $welcomeMessage,
        GamePlay $gamePlay
    ) {
        parent::__construct('bees:play');

        $this->welcomeMessage = $welcomeMessage;
        $this->gamePlay       = $gamePlay;
    }

    /**
     * Configure our command.
     */
    protected function configure(): void
    {
        $this
            ->setProcessTitle('bees')
            ->setDescription('Play Bees In The Trap');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->welcomeMessage->display($output);
        $this->gamePlay->run($input, $output);

        return 0;
    }
}

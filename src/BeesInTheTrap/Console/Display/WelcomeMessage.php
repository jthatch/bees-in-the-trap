<?php

declare(strict_types=1);

namespace BeesInTheTrap\Console\Display;

use Symfony\Component\Console\Output\OutputInterface;

class WelcomeMessage
{
    /** @var int */
    private $beeQuantity;

    /**
     * WelcomeMessage constructor.
     */
    public function __construct(int $beeQuantity)
    {
        $this->beeQuantity = $beeQuantity;
    }

    /**
     * Display the welcome message to the screen.
     */
    public function display(OutputInterface $output): void
    {
        $output->writeln([
            sprintf(
                'Welcome to Bees in The Trap! There are <info>%d</info> buzzin\' bees. Good Luck!',
                $this->beeQuantity
            ),
        ]);
    }
}

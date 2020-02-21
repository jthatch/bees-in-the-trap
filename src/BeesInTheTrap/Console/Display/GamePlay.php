<?php

declare(strict_types=1);

namespace BeesInTheTrap\Console\Display;

use BeesInTheTrap\Module\Trap;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class GamePlay
{
    /** @var Trap */
    private $trap;

    /** @var QuestionHelper */
    private $questionHelper;

    /**
     * WelcomeMessage constructor.
     */
    public function __construct(Trap $trap, QuestionHelper $questionHelper)
    {
        $this->trap           = $trap;
        $this->questionHelper = $questionHelper;
    }

    /**
     * Display the welcome message to the screen.
     */
    public function run(InputInterface $input, OutputInterface $output): void
    {
        $this->trap->build();
        $question = new Question('Type <info>"hit"</info> to attack the trap:');

        while (!$this->trap->isTrapDestroyed()) {
            //do {
            $userInput = $this
                    ->questionHelper
                    ->ask($input, $output, $question);
            //} while ($userInput !== 'hit');

            $output->writeLn(
                $this->trap->hit()
            );
        }
    }
}

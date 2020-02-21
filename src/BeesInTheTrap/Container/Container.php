<?php

declare(strict_types=1);

namespace BeesInTheTrap\Container;

use BeesInTheTrap\Config\Config;
use BeesInTheTrap\Console\Display\GamePlay;
use BeesInTheTrap\Console\Display\WelcomeMessage;
use BeesInTheTrap\Console\PlayCommand;
use BeesInTheTrap\Module\Trap;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\QuestionHelper;

final class Container implements ContainerInterface
{
    /** @var array */
    private $contents;

    /**
     * @return static
     */
    public static function build(): self
    {
        return new self();
    }

    /**
     * @param string $id
     *
     * @return mixed
     *
     * @throws NotFoundException
     */
    public function get($id)
    {
        if (false === $this->has($id)) {
            throw NotFoundException::with($id);
        }

        return $this->contents[$id];
    }

    /**
     * @param string $id
     */
    public function has($id): bool
    {
        return isset($this->contents[$id]);
    }

    /**
     * Container constructor.
     */
    public function __construct()
    {
        $this->contents = $this
            ->console(
                $this
                    ->config()
            );
    }

    private function config(): array
    {
        return Config::get();
    }

    private function console(array $contents): array
    {
        $application     = new Application();
        $questionHelper  = new QuestionHelper();
        $welcomeMessage  = new WelcomeMessage(array_sum(array_column($this->config(), 'quantity')));
        $trap            = new Trap($this->config());
        $gamePlay        = new GamePlay($trap, $questionHelper);

        $consoleCommand  = $application
            ->add(new PlayCommand($welcomeMessage, $gamePlay));

        return array_merge(
            $contents,
            [
                Application::class    => $application,
                WelcomeMessage::class => $welcomeMessage,
                Trap::class           => $trap,
                PlayCommand::class    => $consoleCommand,
            ]
        );
    }
}

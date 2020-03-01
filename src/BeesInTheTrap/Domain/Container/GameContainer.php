<?php

declare(strict_types=1);

namespace BeesInTheTrap\Container;

use BeesInTheTrap\Command\GamePlayCommand;
use BeesInTheTrap\Exception\NotFoundException;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application;

final class GameContainer implements ContainerInterface
{
    private array $contents;

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
            ->console();
    }

    private function console(): array
    {
        $application     = new Application();
        $consoleCommand  = $application
            ->add(new GamePlayCommand());

        return [
                Application::class        => $application,
                GamePlayCommand::class    => $consoleCommand,
        ];
    }
}

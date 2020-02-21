<?php

declare(strict_types=1);

namespace BeesInTheTrap\Container;

use Psr\Container\NotFoundExceptionInterface;

/**
 * Class NotFoundException.
 */
final class NotFoundException extends \Exception implements NotFoundExceptionInterface
{
    public static function with($id): self
    {
        return new self(
            sprintf(
                'Container doesn\'t have service or parameter with id "%s".',
                $id
            )
        );
    }
}

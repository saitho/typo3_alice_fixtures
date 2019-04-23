<?php
declare(strict_types = 1);

namespace Ssch\Typo3AliceFixtures\Integration;

use RuntimeException;

final class ExtensionNotFoundException extends RuntimeException
{
    public static function create(string $extension, array $extensions): ExtensionNotFoundException
    {
        return new static(
            sprintf(
                'The extension "%s" was not found. Extensions available are: ["%s"].',
                $extension,
                implode('", "', $extensions)
            )
        );
    }
}

<?php
declare(strict_types = 1);

namespace App\Model;

final class Genre
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string[]
     */
    private static $allowedNames = [
        'Action & Adventure',
        'Animation',
        'Comedy',
        'Drama',
        'Science Fiction & Fantasy',
    ];

    public function __construct(string $name)
    {
        $name = trim($name);
        if (!in_array($name, self::$allowedNames, true)) {
            throw new \InvalidArgumentException(sprintf(
                'Incorrect name has been passed. Allowed names are: %s',
                implode(', ', self::$allowedNames)
            ));
        }

        $this->name = $name;
    }

    public function equals(Genre $genre): bool
    {
        return $this->name === (string) $genre;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}

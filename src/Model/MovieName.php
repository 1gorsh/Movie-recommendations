<?php
declare(strict_types = 1);

namespace App\Model;

final class MovieName
{
    const MIN_NAME_LENGTH = 2;
    const MAX_NAME_LENGTH = 25;

    /**
     * @var string
     */
    private $name;

    public function __construct(string $name)
    {
        $name = trim($name);
        $nameLength = mb_strlen($name);
        if ($nameLength < self::MIN_NAME_LENGTH || $nameLength > self::MAX_NAME_LENGTH) {
            throw new \InvalidArgumentException(sprintf(
                'Movie name must be between %s ans %s characters',
                self::MIN_NAME_LENGTH,
                self::MAX_NAME_LENGTH
            ));
        }
        $this->name = $name;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}

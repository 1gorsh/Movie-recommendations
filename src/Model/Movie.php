<?php
declare(strict_types = 1);

namespace App\Model;

use Doctrine\Common\Collections\ArrayCollection;

final class Movie
{
    /**
     * @var MovieName
     */
    private $name;

    /**
     * @var int
     */
    private $rating;

    /**
     * @var Genre[]
     */
    private $genres;

    /**
     * @var \DateTimeImmutable[]
     */
    private $showings;

    public function __construct(MovieName $name, int $rating)
    {
        $this->name = $name;
        $this->rating = $rating;
        $this->genres = new ArrayCollection();
        $this->showings = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->name();
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return (string) $this->name;
    }

    /**
     * @return int
     */
    public function rating(): int
    {
        return $this->rating;
    }

    /**
     * @return ArrayCollection
     */
    public function genres(): ArrayCollection
    {
        return $this->genres;
    }

    public function belongsToGenre(Genre $genre): bool
    {
        $genres = $this->genres->filter(function($alreadyAddedGenre) use ($genre) {
            /** @var Genre $alreadyAddedGenre */
            return $alreadyAddedGenre->equals($genre);
        });

        return count($genres) > 0;
    }

    /**
     * @param Genre $genre
     */
    public function tagWithGenre(Genre $genre): void
    {
        $this->genres->add($genre);
    }

    /**
     * @return ArrayCollection
     */
    public function showings(): ArrayCollection
    {
        return $this->showings;
    }

    /**
     * @param \DateTimeImmutable $dateTimeImmutable
     */
    public function showAt(\DateTimeImmutable $dateTimeImmutable)
    {
        $this->showings->add($dateTimeImmutable);
    }

    /**
     * @param \DateTimeImmutable $dateTime
     * @return bool
     */
    public function isAvailableToWatch(\DateTimeImmutable $dateTime): bool
    {
        $showings = $this->showings->filter(function($showing) use ($dateTime) {
            $diff = $dateTime->diff($showing);

            // days converted to minutes + hours converted to minutes + minutes
            $minutes = ($diff->format('%a') * 1440) + ($diff->format('%h') * 60) + $diff->format('%i');

            return $minutes >= 30;
        });

        return count($showings) > 0;
    }
}

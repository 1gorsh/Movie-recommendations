<?php
declare(strict_types = 1);

namespace App\Model;

interface Recommendations
{
    /**
     * @param Movie $movie
     * @return mixed
     */
    public function add(Movie $movie);

    /**
     * @param Genre $genre
     * @param \DateTimeImmutable $dateTime
     * @return iterable
     * @throws RecommendationsNotFound
     */
    public function findByGenreAndTime(Genre $genre, \DateTimeImmutable $dateTime): iterable;
}

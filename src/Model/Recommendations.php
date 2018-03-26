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
     * @return iterable
     * @throws RecommendationsNotFound
     */
    public function findByGenre(Genre $genre): iterable;
}

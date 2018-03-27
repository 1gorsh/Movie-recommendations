<?php
declare(strict_types = 1);

namespace App\Repository;

use App\Model\Genre;
use App\Model\Movie;
use App\Model\MovieName;
use Psr\Log\LoggerInterface;

final class MovieHydrator implements Hydrator
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param array $data
     * @return Movie|null
     */
    public function hydrate(array $data): ?Movie
    {
        $name = $data['name'] ?? '';

        try {
            $name = new MovieName($name);
        } catch (\InvalidArgumentException $e) {
            $this->logger->info(sprintf('Not valid movie data: %s', json_encode($data)));
            return null;
        }

        $rating = $data['rating'] ?? 0;

        $movie = new Movie($name, $rating);

        $genres = $data['genres'] ?? [];
        foreach($genres as $genre) {
            try {
                $genre = new Genre($genre);
            } catch (\InvalidArgumentException $e) {
                $this->logger->info(sprintf('Skipped genre with name: %s, for movie: %s', $genre, (string) $name));
                continue;
            }
            $movie->tagWithGenre($genre);
        }

        $showings = $data['showings'] ?? [];
        foreach($showings as $showing) {
            $movie->showAt(new \DateTimeImmutable($showing));
        }

        return $movie;
    }
}

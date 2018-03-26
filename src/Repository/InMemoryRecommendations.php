<?php
declare(strict_types = 1);

namespace App\Repository;

use App\Model\Movie;
use App\Model\Genre;
use App\Model\Recommendations;
use App\Model\RecommendationsNotFound;
use App\Service\RecommendationApi\RecommendationApi;
use Doctrine\Common\Collections\ArrayCollection;

class InMemoryRecommendations implements Recommendations
{
    /**
     * @var RecommendationApi
     */
    private $apiGateway;

    /**
     * @var ArrayCollection|Movie[]
     */
    private $recommendations;

    public function __construct(RecommendationApi $apiGateway)
    {
        $this->apiGateway = $apiGateway;
        $this->recommendations = $this->apiGateway->getRecommendations();
    }

    public function add(Movie $movie)
    {
        $this->recommendations->add($movie);
    }

    public function findByGenre(Genre $genre): iterable
    {
        $recommendations = $this->recommendations->filter(function($movie) use ($genre) {
            /** @var Movie $movie */
            return $movie->belongsToGenre($genre);
        });

        if (count($recommendations) < 1) {
            throw new RecommendationsNotFound;
        }

        return $recommendations;
    }
}

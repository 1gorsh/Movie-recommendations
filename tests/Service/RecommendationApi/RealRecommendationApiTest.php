<?php
declare(strict_types = 1);

namespace App\Tests\Service\RecommendationApi;

use App\Model\Movie;
use App\Repository\MovieHydrator;
use App\Service\HttpClient\HttpClient;
use App\Service\HttpClient\HttpRequestFailed;
use Doctrine\Common\Collections\ArrayCollection;
use Psr\Log\LoggerInterface;
use App\Service\RecommendationApi\RealRecommendationApi;
use PHPUnit\Framework\TestCase;


class RealRecommendationApiTest extends TestCase
{
    const API_URL = 'https://somecoolapi.com';

    private $httpClient;
    private $logger;
    private $hydrator;

    protected function setUp()
    {
        $this->httpClient = $this->prophesize(HttpClient::class);
        $this->logger = $this->prophesize(LoggerInterface::class);
        $this->hydrator = $this->prophesize(MovieHydrator::class);
    }


    /** @test */
    public function itReturnsRecommendationCorrectly()
    {
        $response = <<<RESPONSE
[
    {
        "name": "Moonlight",
        "rating": 98,
        "genres": [
            "Drama"
        ],
        "showings": [
            "18:30:00+11:00",
            "20:30:00+11:00"
        ]
    },
    {
        "name": "Zootopia",
        "rating": 92,
        "genres": [
            "Action & Adventure",
            "Animation",
            "Comedy"
        ],
        "showings": [
            "19:00:00+11:00",
            "21:00:00+11:00"
        ]
    }
]
RESPONSE;

        $this->httpClient->get(self::API_URL)->willReturn($response);

        $recommendationApi = new RealRecommendationApi(
            self::API_URL,
            $this->httpClient->reveal(),
            new MovieHydrator($this->logger->reveal()),
            $this->logger->reveal()
        );

        /** @var ArrayCollection $recommendations */
        $recommendations = $recommendationApi->getRecommendations();

        $this->assertCount(2, $recommendations);
        $this->assertInstanceOf(ArrayCollection::class, $recommendations);
        $this->assertInstanceOf(Movie::class, $recommendations->first());
        $this->assertInstanceOf(Movie::class, $recommendations->last());
    }

    /**
     * @test
     * @expectedException \App\Service\RecommendationApi\ApiException
     */
    public function itThrowsAnExceptionWhenTheHttpRequestFails()
    {
        $this->httpClient->get(self::API_URL)->willThrow(HttpRequestFailed::class);

        $recommendationApi = new RealRecommendationApi(
            self::API_URL,
            $this->httpClient->reveal(),
            $this->hydrator->reveal(),
            $this->logger->reveal()
        );

        $recommendationApi->getRecommendations();
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function itThrowsAnExceptionWhenBadJsonHasBeenReturnedFromApi()
    {
        $response = '[{"name": "Moonlight",';
        $this->httpClient->get(self::API_URL)->willReturn($response);

        $recommendationApi = new RealRecommendationApi(
            self::API_URL,
            $this->httpClient->reveal(),
            $this->hydrator->reveal(),
            $this->logger->reveal()
        );

        $recommendationApi->getRecommendations();
    }
}

<?php
declare(strict_types = 1);

namespace App\Tests\Service\RecommendationApi;

use App\Service\HttpClient\HttpClient;
use App\Service\HttpClient\HttpRequestFailed;
use Psr\Log\LoggerInterface;
use App\Service\RecommendationApi\RealRecommendationApi;
use PHPUnit\Framework\TestCase;


class RealRecommendationApiTest extends TestCase
{
    /** @test */
    public function itReturnsRecommendationCorrectly()
    {
        $url = 'https://somecoolapi.com';
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

        $httpClient = $this->prophesize(HttpClient::class);
        $httpClient->get($url)->willReturn($response);

        $logger = $this->prophesize(LoggerInterface::class);

        $recommendationApi = new RealRecommendationApi($url, $httpClient->reveal(), $logger->reveal());

        $recommendations = $recommendationApi->getRecommendations();

        $this->assertSame(
            json_decode($response, true),
            $recommendations
        );
    }

    /**
     * @test
     * @expectedException \App\Service\RecommendationApi\ApiException
     */
    public function itThrowsAnExceptionWhenTheHttpRequestFails()
    {
        $url = 'https://somebadapi.com';
        $httpClient = $this->prophesize(HttpClient::class);
        $httpClient->get($url)->willThrow(HttpRequestFailed::class);
        $logger = $this->prophesize(LoggerInterface::class);

        $recommendationApi = new RealRecommendationApi($url, $httpClient->reveal(), $logger->reveal());

        $recommendationApi->getRecommendations();
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function itThrowsAnExceptionWhenBadJsonHasBeenReturnedFromApi()
    {
        $url = 'https://somebadapi.com';
        $response = '[{"name": "Moonlight",';
        $httpClient = $this->prophesize(HttpClient::class);
        $httpClient->get($url)->willReturn($response);
        $logger = $this->prophesize(LoggerInterface::class);

        $recommendationApi = new RealRecommendationApi($url, $httpClient->reveal(), $logger->reveal());

        $recommendationApi->getRecommendations();
    }

}

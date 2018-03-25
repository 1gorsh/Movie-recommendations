<?php
declare(strict_types = 1);

namespace App\Service\RecommendationApi;

use App\Service\HttpClient\HttpClient;
use App\Service\HttpClient\HttpRequestFailed;
use Psr\Log\LoggerInterface;

final class RealRecommendationApi implements RecommendationApi
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(string $url, HttpClient $httpClient, LoggerInterface $logger)
    {
        $this->url = $url;
        $this->httpClient = $httpClient;
        $this->logger = $logger;
    }

    /**
     * @return iterable
     * @throws ApiException
     */
    public function getRecommendations(): iterable
    {
        try {
            $recommendations = $this->httpClient->get($this->url);
        } catch (HttpRequestFailed $e) {
            $this->logger->warning($e->getMessage());

            throw new ApiException;
        }

        return json_decode($recommendations, true);
    }
}

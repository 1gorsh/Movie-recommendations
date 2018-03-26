<?php
declare(strict_types = 1);

namespace App\Service\RecommendationApi;

use App\Repository\MovieHydrator;
use App\Service\HttpClient\HttpClient;
use App\Service\HttpClient\HttpRequestFailed;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @var MovieHydrator
     */
    private $hydrator;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        string $url,
        HttpClient $httpClient,
        MovieHydrator $hydrator,
        LoggerInterface $logger
    ){
        $this->url = $url;
        $this->httpClient = $httpClient;
        $this->logger = $logger;
        $this->hydrator = $hydrator;
    }

    private function sendRequest()
    {
        try {
            $recommendations = $this->httpClient->get($this->url);
        } catch (HttpRequestFailed $e) {
            $this->logger->warning($e->getMessage());

            throw new ApiException;
        }

        return $recommendations;
    }

    /**
     * @return iterable
     * @throws ApiException|\RuntimeException
     */
    public function getRecommendations(): iterable
    {
        $response = $this->sendRequest();
        $responseData = json_decode($response, true);

        if (null === $responseData || json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException;
        }

        $recommendations = new ArrayCollection();

        foreach($responseData as $responseItem) {
            $movie = $this->hydrator->hydrate($responseItem);
            if (null !== $movie) {
                $recommendations->add($movie);
            }
        }

        return $recommendations;
    }
}

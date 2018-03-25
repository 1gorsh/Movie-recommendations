<?php
declare(strict_types = 1);

namespace App\Controller;

use App\Service\RecommendationApi\ApiException;
use App\Service\RecommendationApi\RecommendationApi;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/")
 */
final class Recommendation
{
    public function __invoke(RecommendationApi $api): Response
    {
        try {
            $recommendations = $api->getRecommendations();
        } catch(ApiException $e) {
            // would be good to add some flash message
            $recommendations = [];
        }

        return new JsonResponse($recommendations);
    }
}

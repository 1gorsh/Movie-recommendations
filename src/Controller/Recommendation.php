<?php
declare(strict_types = 1);

namespace App\Controller;

use App\Service\RecommendationApi\ApiException;
use App\Service\RecommendationApi\RecommendationApi;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/recommendations/{genre}/{time}")
 */
final class Recommendation
{
    /**
     * @param string $genre
     * @param string $time
     * @param RecommendationApi $api
     * @return Response
     */
    public function __invoke(string $genre, string $time, RecommendationApi $api): Response
    {
        try {
            $recommendations = new ArrayCollection($api->getRecommendations());
            dump(
                $recommendations
                    ->filter(function($item) {
                        return in_array('Comedy', $item['genres'], true);
                    })
            );
        } catch(ApiException|\RuntimeException $e) {
            // would be good to add some flash message
            $recommendations = [];
        }

        return new JsonResponse($recommendations);
    }
}

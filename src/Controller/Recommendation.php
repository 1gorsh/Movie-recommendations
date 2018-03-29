<?php
declare(strict_types = 1);

namespace App\Controller;

use App\Model\Genre;
use App\Model\Recommendations;
use App\Service\RecommendationApi\ApiException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Model\RecommendationsNotFound;
use Twig\Environment;

/**
 * @Route("/recommendations")
 */
final class Recommendation
{
    /**
     * Movie Recommendations http handler
     * @param Request $request
     * @param Recommendations $recommendationsRepository
     * @param Environment $twig
     * @return Response
     */
    public function __invoke(Request $request, Recommendations $recommendationsRepository, Environment $twig): Response
    {
        try {
            $recommendations = $recommendationsRepository->findByGenreAndTime(
                new Genre($request->query->get('genre', '')),
                new \DateTimeImmutable($request->query->get('time', 'now'))
            );
        } catch(ApiException|\RuntimeException $e) {
            // would be good to add some flash message about api communication/bad response problem
            $recommendations = [];
        } catch(RecommendationsNotFound|\InvalidArgumentException $e) {
            // would be good to add some flash message that there are no results
            $recommendations = [];
        }

        $html = $twig->render('recommendation/list.html.twig', [
            'recommendations' => $recommendations,
        ]);

        return new Response($html);
    }
}

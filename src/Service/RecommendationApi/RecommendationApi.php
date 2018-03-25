<?php
declare(strict_types = 1);

namespace App\Service\RecommendationApi;

interface RecommendationApi
{
    public function getRecommendations(): iterable;
}

<?php
declare(strict_types = 1);

namespace App\Service\HttpClient;

interface HttpClient
{
    /**
     * @param string $url
     * @return string|null Response body
     */
    public function get(string $url);
}

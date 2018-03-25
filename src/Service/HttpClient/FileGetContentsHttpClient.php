<?php
declare(strict_types = 1);

namespace App\Service\HttpClient;

final class FileGetContentsHttpClient implements HttpClient
{
    /**
     * @param string $url
     * @return string
     * @throws HttpRequestFailed
     */
    public function get(string $url): string
    {
        $response = file_get_contents($url);
        if (!$response) {
            throw new HttpRequestFailed();
        }

        return $response;
    }
}

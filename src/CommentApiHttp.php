<?php

declare(strict_types=1);

namespace App;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CommentApiHttp
{
    private HttpClientInterface $httpClient;
    private string $commentsServiceHost;

    public function __construct(HttpClientInterface $httpClient, string $commentsServiceHost)
    {
        $this->httpClient          = $httpClient;
        $this->commentsServiceHost = $commentsServiceHost;
    }

    public function list(int $limit, int $offset): array
    {
        $response = $this->httpClient->request('GET', $this->commentsServiceHost . '/comments', [
            'query' => [
                'limit'  => $limit,
                'offset' => $offset,
            ],
        ]);

        return $response->toArray();
    }

    public function add(): int
    {
        return 0;
    }

    public function update(): void
    {
        return;
    }
}
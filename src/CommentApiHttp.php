<?php

declare(strict_types=1);

namespace CommentClientDrom;

use SplFixedArray;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CommentApiHttp
{
    private HttpClientInterface $httpClient;
    private CommentMapper       $mapper;
    private string              $commentsServiceHost;

    public function __construct(HttpClientInterface $httpClient, CommentMapper $mapper, string $commentsServiceHost)
    {
        $this->httpClient          = $httpClient;
        $this->mapper              = $mapper;
        $this->commentsServiceHost = $commentsServiceHost;
    }

    public function list(int $limit, int $offset): SplFixedArray
    {
        $response = $this->httpClient->request('GET', $this->commentsServiceHost . '/comments', [
            'query' => [
                'limit'  => $limit,
                'offset' => $offset,
            ],
        ]);

        $commentsServiceResponse = $response->toArray();

        return $this->mapper->collectionFromService($commentsServiceResponse);
    }

    public function add(Comment $comment): int
    {
        $response = $this->httpClient->request('POST', $this->commentsServiceHost . '/comment', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'name' => $comment->getName(),
                'text' => $comment->getText(),
            ],
        ]);

        $result = $response->toArray();

        return $result['id'];
    }

    public function update(Comment $comment): array
    {
        $response = $this->httpClient->request('PUT', $this->commentsServiceHost . '/comment/' . $comment->getId(), [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'name' => $comment->getName(),
                'text' => $comment->getText(),
            ],
        ]);

        return [
            'statusCode' => $response->getStatusCode(),
            'content'    => $response->getContent(),
        ];
    }
}

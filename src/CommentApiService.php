<?php

declare(strict_types=1);

namespace App;

use SplFixedArray;
use Symfony\Component\HttpFoundation\JsonResponse;

class CommentApiService
{
    private CommentApiHttp $apiService;
    private CommentMapper  $mapper;

    public function __construct(CommentApiHttp $apiService, CommentMapper $mapper)
    {
        $this->apiService = $apiService;
        $this->mapper     = $mapper;
    }

    public function list(int $limit = 100, int $offset = 0): SplFixedArray
    {
        $commentsServiceResponse = $this->apiService->list($limit, $offset);

        return $this->mapper->collectionFromService($commentsServiceResponse);
    }

    public function addComment(Comment $comment): int
    {
        return $this->apiService->add($comment);
    }

    public function updateComment(Comment $comment): bool
    {
        $result = $this->apiService->update($comment);

        return $result['statusCode'] === JsonResponse::HTTP_NO_CONTENT;
    }
}

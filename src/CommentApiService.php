<?php

declare(strict_types=1);

namespace App;

use SplFixedArray;

class CommentApiService
{
    private CommentApiHttp $commentApiService;
    private CommentMapper  $mapper;

    public function __construct(CommentApiHttp $commentApiService, CommentMapper $mapper)
    {
        $this->commentApiService = $commentApiService;
        $this->mapper = $mapper;
    }

    public function list(int $limit = 100, int $offset = 0): SplFixedArray
    {
        $commentsServiceResponse = $this->commentApiService->list($limit, $offset);

        return $this->mapper->collectionFromService($commentsServiceResponse);
    }
}
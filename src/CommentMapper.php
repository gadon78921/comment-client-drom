<?php

declare(strict_types=1);

namespace CommentClientDrom;

use SplFixedArray;

class CommentMapper
{
    public function collectionFromService(array $serviceResponses): SplFixedArray
    {
        $collection = new SplFixedArray(count($serviceResponses));

        foreach ($serviceResponses as $response) {
            $comments[] = $this->entityFromService($response);
        }

        return $collection::fromArray($comments ?? []);
    }

    public function entityFromService(array $serviceData): Comment
    {
        $entity = new Comment();
        $entity->setId($serviceData['id']);
        $entity->setName($serviceData['name']);
        $entity->setText($serviceData['text']);

        return $entity;
    }
}

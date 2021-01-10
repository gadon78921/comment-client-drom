<?php

namespace App\Tests;

use App\Comment;
use App\CommentApiHttp;
use App\CommentApiService;
use App\CommentMapper;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;

class CommentApiServiceTest  extends KernelTestCase
{
    public function testList()
    {
        $commentsServiceResponse = [
            [
                'id'   => 1,
                'name' => 'Ivan',
                'text' => 'comment_text_1',
            ],
            [
                'id'   => 2,
                'name' => 'Petr',
                'text' => 'comment_text_2',
            ],
            [
                'id'   => 3,
                'name' => 'Givi',
                'text' => 'comment_text_3',
            ],
        ];

        $commentApiService = $this->createMock(CommentApiHttp::class);
        $commentApiService->method('list')->willReturn($commentsServiceResponse);

        $commentMapper     = new CommentMapper();
        $commentApiService = new CommentApiService($commentApiService, $commentMapper);

        $commentsCollection = $commentApiService->list();

        $this->assertEquals(3, $commentsCollection->count());

        foreach ($commentsCollection as $index => $comment) {
            /** @var Comment $comment */
            $this->assertEquals($commentsServiceResponse[$index]['id'], $comment->getId());
            $this->assertEquals($commentsServiceResponse[$index]['name'], $comment->getName());
            $this->assertEquals($commentsServiceResponse[$index]['text'], $comment->getText());
        }
    }

    public function testEmpty()
    {
        $commentsServiceResponse = [];

        $commentApiService = $this->createMock(CommentApiHttp::class);
        $commentApiService->method('list')->willReturn($commentsServiceResponse);

        $commentMapper     = new CommentMapper();
        $commentApiService = new CommentApiService($commentApiService, $commentMapper);

        $commentsCollection = $commentApiService->list();

        $this->assertEquals(0, $commentsCollection->count());
    }

    public function testException()
    {
        $commentApiService = $this->createMock(CommentApiHttp::class);
        $commentApiService->method('list')->willThrowException($this->createMock(ServerExceptionInterface::class));

        $commentMapper     = new CommentMapper();
        $commentApiService = new CommentApiService($commentApiService, $commentMapper);

        $this->expectException(ServerExceptionInterface::class);
        $commentsCollection = $commentApiService->list();
    }
}
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
    private $mockCommentApiService;
    private $commentMapper;

    protected function setUp(): void
    {
        $this->mockCommentApiService = $this->createMock(CommentApiHttp::class);
        $this->commentMapper         = new CommentMapper();
    }

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

        $this->mockCommentApiService->method('list')->willReturn($commentsServiceResponse);

        $commentApiService = new CommentApiService($this->mockCommentApiService, $this->commentMapper);

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
        $this->mockCommentApiService->method('list')->willReturn($commentsServiceResponse);

        $commentApiService = new CommentApiService($this->mockCommentApiService, $this->commentMapper);

        $commentsCollection = $commentApiService->list();

        $this->assertEquals(0, $commentsCollection->count());
    }

    public function testException()
    {
        $this->mockCommentApiService->method('list')->willThrowException($this->createMock(ServerExceptionInterface::class));

        $commentApiService = new CommentApiService($this->mockCommentApiService, $this->commentMapper);

        $this->expectException(ServerExceptionInterface::class);
        $commentApiService->list();
    }

    public function testAddComment()
    {
        $expectedNewCommentId = 5;
        $this->mockCommentApiService->method('add')->willReturn($expectedNewCommentId);

        $commentApiService = new CommentApiService($this->mockCommentApiService, $this->commentMapper);

        $newComment = new Comment();
        $newComment->setName('Ivan');
        $newComment->setText('Comment_text');

        $newCommentId = $commentApiService->addComment($newComment);

        $this->assertEquals($expectedNewCommentId, $newCommentId);
    }

    public function testAddException()
    {
        $this->mockCommentApiService->method('add')->willThrowException($this->createMock(ServerExceptionInterface::class));

        $commentApiService = new CommentApiService($this->mockCommentApiService, $this->commentMapper);

        $newComment = new Comment();
        $newComment->setName('Ivan');
        $newComment->setText('Comment_text');

        $this->expectException(ServerExceptionInterface::class);
        $commentApiService->addComment($newComment);
    }
}

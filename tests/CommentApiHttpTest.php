<?php

namespace CommentClientDrom\Tests;

use CommentClientDrom\Comment;
use CommentClientDrom\CommentApiHttp;
use CommentClientDrom\CommentMapper;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;

class CommentApiHttpTest extends KernelTestCase
{
    private const COMMENT_SERVICE_HOST = 'http://example.com';

    public function testList()
    {
        $commentsList = [
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

        $mockApiResponse = [
            new MockResponse(json_encode($commentsList), ['http_code' => 200]),
        ];
        $mockHttpClient = new MockHttpClient($mockApiResponse);

        $commentApiHttp     = new CommentApiHttp($mockHttpClient, new CommentMapper(), self::COMMENT_SERVICE_HOST);
        $commentsCollection = $commentApiHttp->list(100, 0);

        $this->assertEquals(3, $commentsCollection->count());
        foreach ($commentsCollection as $index => $comment) {
            /** @var Comment $comment */
            $this->assertEquals($commentsList[$index]['id'], $comment->getId());
            $this->assertEquals($commentsList[$index]['name'], $comment->getName());
            $this->assertEquals($commentsList[$index]['text'], $comment->getText());
        }
    }

    public function testEmptyList()
    {
        $commentsList = [];

        $mockApiResponse = [
            new MockResponse(json_encode($commentsList), ['http_code' => 200]),
        ];
        $mockHttpClient = new MockHttpClient($mockApiResponse);

        $commentApiHttp     = new CommentApiHttp($mockHttpClient, new CommentMapper(), self::COMMENT_SERVICE_HOST);
        $commentsCollection = $commentApiHttp->list(100, 0);

        $this->assertEquals(0, $commentsCollection->count());
    }

    public function testListException()
    {
        $commentsList = [];

        $mockApiResponse = [
            new MockResponse(json_encode($commentsList), ['http_code' => 500]),
        ];
        $mockHttpClient = new MockHttpClient($mockApiResponse);

        $commentApiHttp     = new CommentApiHttp($mockHttpClient, new CommentMapper(), self::COMMENT_SERVICE_HOST);

        $this->expectException(ServerExceptionInterface::class);
        $commentApiHttp->list(100, 0);
    }

    public function testAdd()
    {
        $comment = new Comment();
        $comment->setName('Ivan');
        $comment->setText('Comment_text');

        $mockApiResponse = [
            new MockResponse(json_encode(['id' => 5]), ['http_code' => 201]),
        ];
        $mockHttpClient = new MockHttpClient($mockApiResponse);

        $commentApiHttp = new CommentApiHttp($mockHttpClient, new CommentMapper(), self::COMMENT_SERVICE_HOST);
        $response       = $commentApiHttp->add($comment);

        $this->assertEquals(5, $response);
    }

    public function testAddException()
    {
        $comment = new Comment();
        $comment->setName('Ivan');
        $comment->setText('Comment_text');

        $mockApiResponse = [
            new MockResponse(json_encode(['id' => 5]), ['http_code' => 500]),
        ];
        $mockHttpClient = new MockHttpClient($mockApiResponse);

        $commentApiHttp     = new CommentApiHttp($mockHttpClient, new CommentMapper(), self::COMMENT_SERVICE_HOST);

        $this->expectException(ServerExceptionInterface::class);
        $commentApiHttp->add($comment);
    }

    public function testUpdate()
    {
        $comment = new Comment();
        $comment->setId(5);
        $comment->setName('Ivan');
        $comment->setText('Comment_text');

        $mockApiResponse = [
            new MockResponse('', ['http_code' => 204]),
        ];
        $mockHttpClient = new MockHttpClient($mockApiResponse);

        $commentApiHttp = new CommentApiHttp($mockHttpClient, new CommentMapper(), self::COMMENT_SERVICE_HOST);
        $response       = $commentApiHttp->update($comment);

        $this->assertEquals(204, $response['statusCode']);
    }

    public function testUpdateException()
    {
        $comment = new Comment();
        $comment->setId(5);
        $comment->setName('Ivan');
        $comment->setText('Comment_text');

        $mockApiResponse = [
            new MockResponse('', ['http_code' => 500]),
        ];
        $mockHttpClient = new MockHttpClient($mockApiResponse);

        $commentApiHttp = new CommentApiHttp($mockHttpClient, new CommentMapper(), self::COMMENT_SERVICE_HOST);

        $this->expectException(ServerExceptionInterface::class);
        $commentApiHttp->update($comment);
    }
}

<?php

namespace App\Tests;

use App\Comment;
use App\CommentApiHttp;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;

class CommentApiHttpTest extends KernelTestCase
{
    private const COMMENT_SERVICE_HOST = 'http://example.com';

    protected function setUp(): void
    {
        self::bootKernel();
    }

    public function testList()
    {
        $commentsList = [
            [
                'id'   => 1,
                'name' => 'Ivan',
                'test' => 'comment_text_1',
            ],
            [
                'id'   => 2,
                'name' => 'Petr',
                'test' => 'comment_text_2',
            ],
            [
                'id'   => 3,
                'name' => 'Givi',
                'test' => 'comment_text_3',
            ],
        ];

        $mockApiResponse = [
            new MockResponse(json_encode($commentsList), ['http_code' => 200]),
        ];
        $mockHttpClient = new MockHttpClient($mockApiResponse);

        $commentApiHttp = new CommentApiHttp($mockHttpClient, self::COMMENT_SERVICE_HOST);
        $response       = $commentApiHttp->list(100, 0);

        $this->assertEquals($commentsList, $response);
    }

    public function testEmptyList()
    {
        $commentsList = [];

        $mockApiResponse = [
            new MockResponse(json_encode($commentsList), ['http_code' => 200]),
        ];
        $mockHttpClient = new MockHttpClient($mockApiResponse);

        $commentApiHttp = new CommentApiHttp($mockHttpClient, self::COMMENT_SERVICE_HOST);
        $response       = $commentApiHttp->list(100, 0);

        $this->assertEquals($commentsList, $response);
    }

    public function testListException()
    {
        $commentsList = [];

        $mockApiResponse = [
            new MockResponse(json_encode($commentsList), ['http_code' => 500]),
        ];
        $mockHttpClient = new MockHttpClient($mockApiResponse);

        $commentApiHttp = new CommentApiHttp($mockHttpClient, self::COMMENT_SERVICE_HOST);

        $this->expectException(ServerExceptionInterface::class);
        $commentApiHttp->list(100, 0);
    }

    public function testAdd()
    {
        $comment = new Comment();
        $comment->setName('Ivan');
        $comment->setText('Comment_text');

        $mockApiResponse = [
            new MockResponse(json_encode(['id' => 5]), ['http_code' => 200]),
        ];
        $mockHttpClient = new MockHttpClient($mockApiResponse);

        $commentApiHttp = new CommentApiHttp($mockHttpClient, self::COMMENT_SERVICE_HOST);
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

        $commentApiHttp = new CommentApiHttp($mockHttpClient, self::COMMENT_SERVICE_HOST);

        $this->expectException(ServerExceptionInterface::class);
        $commentApiHttp->add($comment);
    }
}

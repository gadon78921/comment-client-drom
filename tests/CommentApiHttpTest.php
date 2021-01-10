<?php

namespace App\Tests;

use App\CommentApiHttp;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;

class CommentApiHttpTest extends KernelTestCase
{
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

        $commentApiHttp = new CommentApiHttp($mockHttpClient, 'http://example.com');
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

        $commentApiHttp = new CommentApiHttp($mockHttpClient, 'http://example.com');
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

        $commentApiHttp = new CommentApiHttp($mockHttpClient, 'http://example.com');

        $this->expectException(ServerExceptionInterface::class);
        $response = $commentApiHttp->list(100, 0);
    }
}
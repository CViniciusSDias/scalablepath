<?php

namespace App\Tests;

use App\Entity\Post;
use App\Repository\ApiDoctrinePostRepositoryDecorator;
use App\Repository\DoctrinePostRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiDoctrinePostRepositoryDecoratorTest extends TestCase
{
    public function testApiShouldOnlyBeCalledIfDatabaseIsEmpty(): void
    {
        // Arrange
        $post = new Post(1, '', '', '');

        $doctrineRepo = $this->createStub(DoctrinePostRepository::class);
        $doctrineRepo
            ->method('all')
            ->willReturn([$post]);

        $sut = new ApiDoctrinePostRepositoryDecorator($doctrineRepo, $this->createStub(HttpClientInterface::class));

        // Act
        $posts = $sut->all();

        // Assert
        self::assertSame([$post], $posts);
    }
}

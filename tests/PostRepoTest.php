<?php

namespace App\Tests;

use App\Entity\Post;
use App\Repository\ApiPostRepository;
use App\Repository\DoctrinePostRepository;
use App\Repository\PostRepositoryChain;
use PHPUnit\Framework\TestCase;

class PostRepoTest extends TestCase
{
    public function testSomething(): void
    {
        // Arrange
        $post = new Post(1, '', '', '');

        $doctrineRepo = $this->createStub(DoctrinePostRepository::class);
        $doctrineRepo
            ->method('all')
            ->willReturn([$post]);

        $apiRepo = $this->createMock(ApiPostRepository::class);
        $apiRepo->expects($this->never())->method('all');

        $sut = new PostRepositoryChain($doctrineRepo, $apiRepo);

        // Act
        $posts = $sut->all();

        // Assert
        self::assertSame([$post], $posts);
    }
}

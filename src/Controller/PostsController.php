<?php

namespace App\Controller;

use App\Repository\PostRemover;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;

class PostsController extends AbstractController
{
    public function __construct(
        private PostRepository $postRepository,
        private CacheInterface $cache,
        private PostRemover $postRemover,
    ) {
    }

    #[Route('/posts', name: 'app_posts', methods: ['GET'])]
    public function listPosts(): JsonResponse
    {
        return $this->json($this->postRepository->all());
    }

    #[Route('/posts/{postId}', name: 'delete_post', methods: ['GET'])]
    public function deletePost(int $postId): Response
    {
        $this->postRemover->removeById($postId);
        $this->cache->delete('posts');

        return new Response('', 204);
    }
}

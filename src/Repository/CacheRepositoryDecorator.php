<?php

namespace App\Repository;

use Symfony\Contracts\Cache\CacheInterface;

class CacheRepositoryDecorator implements PostRepository
{
    public function __construct(private PostRepository $repository, private CacheInterface $cache)
    {
    }

    public function all(): iterable
    {
        return $this->cache->get('posts', fn () => $this->repository->all());
    }
}

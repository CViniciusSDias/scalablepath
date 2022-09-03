<?php

namespace App\Repository;

use App\Entity\Post;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ApiDoctrinePostRepositoryDecorator implements PostRepository
{
    public function __construct(private DoctrinePostRepository $doctrineRepository, private HttpClientInterface $httpClient)
    {
    }

    public function all(): iterable
    {
        $posts = $this->doctrineRepository->all();
        if (!empty($posts)) {
            return $posts;
        }

        [$postsResponse, $usersResponse] = $this->fetchPostsAndUsers();
        $posts = $this->mergeResponses($postsResponse, $usersResponse);
        $this->doctrineRepository->addAll($posts);

        return $posts;
    }

    private function fetchPostsAndUsers(): array
    {
        $postsResponse = $this->httpClient
            ->request('GET', 'https://www.scalablepath.com/api/test/test-posts');
        $usersResponse = $this->httpClient
            ->request('GET', 'https://www.scalablepath.com/api/test/test-users');

        return [$postsResponse, $usersResponse];
    }

    /**
     * @param array $fetchPostsAndUsers
     * @return Post[]
     */
    private function mergeResponses(ResponseInterface $postsResponse, ResponseInterface $usersResponse): array
    {
        $postsData = json_decode($postsResponse->getContent(), true);
        $usersData = json_decode($usersResponse->getContent(), true);
        $usersFromId = array_reduce($usersData, function (array $result, array $userData) {
            $result[$userData['id']] = $userData['username'];
            return $result;
        }, []);

        return array_map(fn (array $postData) => new Post(
            $postData['id'],
            $usersFromId[$postData['userId']],
            $postData['title'],
            $postData['body'],
        ), $postsData);
    }
}

<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctrinePostRepository extends ServiceEntityRepository implements PostRepository, PostRemover
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function add(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function addAll(iterable $posts): void
    {
        foreach ($posts as $post) {
            $this->add($post);
        }

        $this->getEntityManager()->flush();
    }

    public function remove(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function all(): iterable
    {
        return $this->createQueryBuilder('post')
            ->where('post.deletedAt IS NULL')
            ->getQuery()
            ->getResult();
    }

    public function removeById(int $postId): void
    {
        $this->getEntityManager()
            ->createQueryBuilder()
            ->update(Post::class, 'post')
            ->set('post.deletedAt', ':deletedAt')
            ->where('post.id = :postId')
            ->setParameter('deletedAt', new \DateTimeImmutable())
            ->setParameter(':postId', $postId)
            ->getQuery()
            ->execute();
    }
}

<?php

namespace App\Entity;

use App\Repository\DoctrinePostRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DoctrinePostRepository::class)]
class Post
{
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deletedAt;

    public function __construct(
        #[ORM\Id]
        #[ORM\Column]
        public readonly ?int $id = null,
        #[ORM\Column(length: 255, name: 'user_name')]
        public readonly ?string $username = null,
        #[ORM\Column(length: 255)]
        public readonly ?string $title = null,
        #[ORM\Column(type: Types::TEXT)]
        public readonly ?string $body = null,
    ) {
        $this->deletedAt = null;
    }

    public function delete(): void
    {
        $this->deletedAt = new \DateTimeImmutable();
    }
}

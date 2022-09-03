<?php

namespace App\Repository;

interface PostRemover
{
    public function removeById(int $postId): void;
}

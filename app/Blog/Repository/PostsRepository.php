<?php

declare (strict_types = 1);

namespace App\Blog\Repository;

use App\Application\Repositories\Repository;
use App\Blog\Entities\Post;

class PostsRepository extends Repository
{
    public function getEntityClass(): string
    {
        return Post::class;
    }

    public function getTableName(): string
    {
        return 'posts';
    }

    public function getRelatedPosts(int $currentPostId, int $limit = 3): array
    {
        $categoryIdsQuery = $this->queryBuilder();
        $categoryIds = $categoryIdsQuery
            ->select('category_id')
            ->from('post_category')
            ->where('post_id = :currentPostId')
            ->setParameter('currentPostId', $currentPostId)
            ->fetchAssociative();

        if (empty($categoryIds)) {
            return [];
        }

        $qb = $this->queryBuilder();

        $qb->select('p.*')
            ->distinct()
            ->from('posts', 'p')
            ->innerJoin('p', 'post_category', 'pc', 'p.id = pc.post_id')
            ->where($qb->expr()->in('pc.category_id', ':catIds'))
            ->andWhere('p.id <> :currentPostId')
            ->orderBy('p.published_at', 'DESC')
            ->setMaxResults($limit)
            ->setParameters([
                'catIds' => $categoryIds,
                'currentPostId' => $currentPostId
            ]);

        return $qb->fetchAllAssociative();
    }
}
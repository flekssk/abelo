<?php

declare(strict_types=1);

namespace App\Blog\Actions;

use App\Application\CQRS\Action;
use App\Application\Request\Request;
use App\Application\View\Facades\ViewFactoryFacade;
use App\Blog\DTO\PostListDTO;
use App\Blog\Repository\PostsRepository;
use Symfony\Component\HttpFoundation\Response;

class PostAction extends Action
{
    public function __construct(public readonly PostsRepository $postsRepository)
    {
    }

    public function handle(int $postId): PostListDTO
    {
        $builder = $this->postsRepository->queryBuilder()
            ->where('post_id = :post_id')
            ->setParameter('post_id', $postId);

        return $builder->fetchOne();
    }

    public function asHtml(int $postId, Request $request): Response
    {
        $post = $this->handle($postId);

        return new Response(
            ViewFactoryFacade::buildView(
                'templates/main.tpl',
                [
                    'contentTpl' => 'file:post.tpl',
                    'pageTitle' => 'Post',
                    'post' => $post,
                    'totalPages' => ceil($post->totalCount / 20),
                    'similarPosts' => $this->postsRepository->getRelatedPosts($postId, 3),
                ]
            )
        );
    }
}
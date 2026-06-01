<?php

declare(strict_types=1);

namespace App\Blog\Actions;

use App\Application\CQRS\Action;
use App\Application\Request\Request;
use App\Application\View\Facades\ViewFactoryFacade;
use App\Blog\DTO\PostListDTO;
use App\Blog\DTO\PostListRequestDTO;
use App\Blog\Repository\PostsRepository;
use Symfony\Component\HttpFoundation\Response;

class PostListAction extends Action
{
    public function __construct(public readonly PostsRepository $postsRepository)
    {
    }

    public function handle(PostListRequestDTO $dto): PostListDTO
    {
        $builder = $this->postsRepository->queryBuilder()
            ->where('category_id = :category_id')
            ->setParameter('category_id', $dto->categoryId);

        $totalCount = clone($builder)->select('COUNT(*)')->fetchOne();

        foreach ($dto->sortBy as $column => $direction) {
            $builder->orderBy($column, $direction);
        }

        if ($dto->page !== null) {
            $builder->setMaxResults($dto->perPage);
            $builder->setFirstResult(($dto->page - 1) * $dto->perPage);
        }

        return new PostListDTO($builder->fetchAssociative(), $totalCount);
    }

    public function asHtml(int $categoryId, Request $request): Response
    {
        $list = $this->handle(new PostListRequestDTO($categoryId, $request->sort, $request->page, 20));

        return new Response(
            ViewFactoryFacade::buildView(
                'templates/main.tpl',
                [
                    'contentTpl' => 'file:category.tpl',
                    'pageTitle' => 'Category',
                    'currentPage' => $request->page,
                    'posts' => $list,
                    'totalPages' => ceil($list->totalCount / 20)
                ]
            )
        );
    }
}
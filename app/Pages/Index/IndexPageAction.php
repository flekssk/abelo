<?php

declare(strict_types=1);

namespace App\Pages\Index;

use App\Application\CQRS\Action;
use App\Application\Request\Request;
use App\Application\View\Facades\ViewFactoryFacade;
use App\Blog\Repository\PostsRepository;
use Symfony\Component\HttpFoundation\Response;

class IndexPageAction extends Action
{
    public function __construct(public readonly PostsRepository $postsRepository)
    {
    }

    public function handle(): array
    {
        $qb = $this->postsRepository->queryBuilder();

        $subQb = $this->postsRepository->queryBuilder();
        $subQb->select(
            'c.id AS category_id',
            'c.name AS category_name',
            'c.description AS category_description',
            'p.id AS post_id',
            'p.title AS post_title',
            'p.description AS post_description',
            'p.image AS post_image',
            'p.published_at',
            'p.views_count',
            'ROW_NUMBER() OVER (PARTITION BY c.id ORDER BY p.published_at DESC) as rn'
        )
            ->from('categories', 'c')
            ->innerJoin('c', 'post_category', 'pc', 'c.id = pc.category_id')
            ->innerJoin('pc', 'posts', 'p', 'pc.post_id = p.id');

        $qb->select('*')
            ->from('(' . $subQb->getSQL() . ')', 'ranked_posts')
            ->where('rn <= 3')
            ->orderBy('category_id', 'ASC')
            ->addOrderBy('published_at', 'DESC');

        return $qb->fetchAllAssociative();
    }

    public function asHtml(Request $request): Response
    {
        return new Response(
            ViewFactoryFacade::buildView(
                'templates/main.tpl',
                [
                    'contentTpl' => 'file:main.tpl',
                    'pageTitle' => 'Main',
                    'categories' => $this->handle()
                ]
            )
        );
    }
}
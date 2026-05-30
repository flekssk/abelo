<?php

declare(strict_types=1);

namespace App\Pages\Index;

use App\Application\CQRS\Action;
use App\Application\View\Facades\ViewFactoryFacade;
use Symfony\Component\HttpFoundation\Response;

class IndexPageAction extends Action
{
    public function asController(): Response
    {
        return new Response(
            ViewFactoryFacade::buildView(
                'templates/main.tpl',
                [
                    'content_tpl' => 'file:main.tpl',
                    'page_title' => 'Test',
                ]
            )
        );
    }
}
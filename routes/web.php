<?php

declare(strict_types=1);

use App\Application\Router\Facades\RouterFacade;
use App\Blog\Actions\PostAction;
use App\Blog\Actions\PostListAction;
use App\Pages\Index\IndexPageAction;

RouterFacade::get('', IndexPageAction::class);
RouterFacade::get('category/{categoryId}', PostListAction::class);
RouterFacade::get('post/{postId}', PostAction::class);
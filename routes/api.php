<?php

declare(strict_types=1);

use App\Application\Router\Facades\RouterFacade;
use App\Pages\Index\IndexPageAction;

RouterFacade::get('', IndexPageAction::class);
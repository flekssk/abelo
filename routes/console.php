<?php

declare(strict_types=1);

use App\Application\Console\Facades\ConsoleRouterFacade;
use App\Application\Database\Actions\MigrateAction;

ConsoleRouterFacade::add(MigrateAction::class);
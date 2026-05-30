<?php

declare(strict_types=1);

namespace App\Pages\Index;

use App\Application\CQRS\Action;

class IndexPageAction extends Action
{
    public function asController()
    {
        echo 111;
    }
}
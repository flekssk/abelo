<?php

declare(strict_types=1);

namespace App\Application\CQRS;

use App\Application\CQRS\Concrete\AsCommand;
use App\Application\CQRS\Concrete\AsHtml;

class Action
{
    use AsHtml;
    use AsCommand;
}
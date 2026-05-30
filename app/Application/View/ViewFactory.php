<?php

declare(strict_types=1);

namespace App\Application\View;

use Smarty\Smarty;

class ViewFactory
{
    public function buildView(string $template, array $data = []): string
    {
        $smarty = $this->buildSmarty();

        foreach ($data as $key => $value) {
            $smarty->assign($key, $value);
        }

        return $smarty->fetch($template);
    }

    public function buildSmarty(): Smarty
    {
        $smarty = new Smarty();
        $smarty->setTemplateDir(ROOT_DIR . 'views');
        $smarty->setCompileDir(ROOT_DIR . 'storage/smarty/compile');
        $smarty->setCacheDir(ROOT_DIR . 'storage/smarty/cache');

        return $smarty;
    }
}
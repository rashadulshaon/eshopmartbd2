<?php

namespace App\Twig;

use App\Repository\CategoryRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ClassExtension extends AbstractExtension
{
    public function __construct(
        private CategoryRepository $categoryRepo,
    ) {
    }
    public function getFunctions()
    {
        return [
            new TwigFunction('getClass', [$this, 'getClass']),
        ];
    }

    public function getClass(object $obj)
    {
        return get_class($obj);
    }
}

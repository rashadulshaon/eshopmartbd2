<?php

namespace App\Twig;

use App\Repository\CategoryRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CategoryExtension extends AbstractExtension
{
    public function __construct(
        private CategoryRepository $categoryRepo,
    ) {}
    public function getFunctions()
    {
        return [
            new TwigFunction('renderCategory', [$this, 'renderCategory']),
        ];
    }

    public function renderCategory()
    {
        return $this->categoryRepo->findBy([], null, 4);
    }
}

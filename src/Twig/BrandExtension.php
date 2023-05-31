<?php

namespace App\Twig;

use App\Repository\BrandRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class BrandExtension extends AbstractExtension
{
    public function __construct(
        private BrandRepository $brandRepo,
    ) {
    }
    public function getFunctions()
    {
        return [
            new TwigFunction('renderBrand', [$this, 'renderBrand']),
        ];
    }

    public function renderBrand()
    {
        return $this->brandRepo->findAll();
    }
}

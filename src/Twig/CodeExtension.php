<?php

namespace App\Twig;

use App\Repository\CodeRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CodeExtension extends AbstractExtension
{
    public function __construct(
        private CodeRepository $codeRepo,
    ) {
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('renderCodes', [$this, 'renderCodes']),
        ];
    }

    public function renderCodes()
    {
        return $this->codeRepo->findAll();
    }
}

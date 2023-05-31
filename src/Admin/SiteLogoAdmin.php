<?php

declare(strict_types=1);

namespace App\Admin;

use App\Form\ProductImageType;
use App\Repository\SiteLogoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;

final class SiteLogoAdmin extends AbstractAdmin
{
    public function __construct(
        private EntityManagerInterface $em,
        private SiteLogoRepository $siteLogoRepo
    ) {
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection
            ->remove('list')
        ;
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('image', ProductImageType::class)
        ;
    }

    public function prePersist(object $siteLogo): void
    {
        $siteLogos = $this->siteLogoRepo->findAll();

        foreach ($siteLogos as $logo) {
            $image = $logo->getImage();
            $this->em->remove($image);
            $this->em->remove($logo);
        }
    }
}

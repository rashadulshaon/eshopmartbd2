<?php

namespace App\EntityListener;

use App\Entity\Product;
use App\Entity\Report;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;

class ProductEntityListener
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function postPersist(Product $product, LifecycleEventArgs $event)
    {
        $report = (new Report())->setProduct($product);
        $this->em->persist($report);
        $this->em->flush();
    }

    public function postRemove(Product $product, LifecycleEventArgs $event)
    {
        $this->em->remove($product->getReport());
        $this->em->flush();
    }
}

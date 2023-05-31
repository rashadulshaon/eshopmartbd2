<?php

namespace App\Entity;

use App\Repository\SiteLogoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SiteLogoRepository::class)]
class SiteLogo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\OneToOne(targetEntity: ProductImage::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $image;

    public function __toString()
    {
        return $this->id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?ProductImage
    {
        return $this->image;
    }

    public function setImage(ProductImage $image): self
    {
        $this->image = $image;

        return $this;
    }
}

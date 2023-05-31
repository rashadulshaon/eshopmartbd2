<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'text', nullable: true)]
    private $description;

    #[ORM\Column(type: 'string', length: 255)]
    private $price;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private $category;

    #[ORM\OneToOne(targetEntity: ProductImage::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $firstImage;

    #[ORM\OneToOne(targetEntity: ProductImage::class, cascade: ['persist', 'remove'])]
    private $secondImage;

    #[ORM\OneToOne(targetEntity: ProductImage::class, cascade: ['persist', 'remove'])]
    private $thirdImage;

    #[ORM\Column(type: 'string', length: 480, nullable: true)]
    private $summary;

    #[ORM\ManyToOne(targetEntity: Brand::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private $brand;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $wholesalePrice;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $regularPrice;

    #[ORM\Column(type: 'boolean')]
    private $isStockOut = false;

    #[ORM\OneToOne(mappedBy: 'product', targetEntity: Report::class, cascade: ['persist', 'remove'])]
    private $report;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: OrderItem::class, orphanRemoval: true)]
    private $orderItems;

    public function __construct()
    {
        $this->orderItems = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getFirstImage(): ?ProductImage
    {
        return $this->firstImage;
    }

    public function setFirstImage(ProductImage $firstImage): self
    {
        $this->firstImage = $firstImage;

        return $this;
    }

    public function getSecondImage(): ?ProductImage
    {
        return $this->secondImage;
    }

    public function setSecondImage(?ProductImage $secondImage): self
    {
        $this->secondImage = $secondImage;

        return $this;
    }

    public function getThirdImage(): ?ProductImage
    {
        return $this->thirdImage;
    }

    public function setThirdImage(?ProductImage $thirdImage): self
    {
        $this->thirdImage = $thirdImage;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getWholesalePrice(): ?int
    {
        return $this->wholesalePrice;
    }

    public function setWholesalePrice(?int $wholesalePrice): self
    {
        $this->wholesalePrice = $wholesalePrice;

        return $this;
    }

    public function getRegularPrice(): ?string
    {
        return $this->regularPrice;
    }

    public function setRegularPrice(?string $regularPrice): self
    {
        $this->regularPrice = $regularPrice;

        return $this;
    }

    public function isIsStockOut(): ?bool
    {
        return $this->isStockOut;
    }

    public function setIsStockOut(bool $isStockOut): self
    {
        $this->isStockOut = $isStockOut;

        return $this;
    }

    public function getReport(): ?Report
    {
        return $this->report;
    }

    public function setReport(Report $report): self
    {
        // set the owning side of the relation if necessary
        if ($report->getProduct() !== $this) {
            $report->setProduct($this);
        }

        $this->report = $report;

        return $this;
    }

    /**
     * @return Collection<int, OrderItem>
     */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    public function addOrderItem(OrderItem $orderItem): self
    {
        if (!$this->orderItems->contains($orderItem)) {
            $this->orderItems[] = $orderItem;
            $orderItem->setProduct($this);
        }

        return $this;
    }

    public function removeOrderItem(OrderItem $orderItem): self
    {
        if ($this->orderItems->removeElement($orderItem)) {
            // set the owning side to null (unless already changed)
            if ($orderItem->getProduct() === $this) {
                $orderItem->setProduct(null);
            }
        }

        return $this;
    }
}

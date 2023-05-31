<?php

namespace App\Entity;

use App\Repository\ReportRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReportRepository::class)]
class Report
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\OneToOne(inversedBy: 'report', targetEntity: Product::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $product;

    #[ORM\Column(type: 'integer')]
    private $totalOrder = 0;

    #[ORM\Column(type: 'integer')]
    private $pendingOrder = 0;

    #[ORM\Column(type: 'integer')]
    private $onHoldOrder = 0;

    #[ORM\Column(type: 'integer')]
    private $paymentPendingOrder = 0;

    #[ORM\Column(type: 'integer')]
    private $shippedOrder = 0;

    #[ORM\Column(type: 'integer')]
    private $canceledOrder = 0;

    #[ORM\Column(type: 'integer')]
    private $completedOrder = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getTotalOrder(): ?int
    {
        return $this->totalOrder;
    }

    public function setTotalOrder(int $totalOrder): self
    {
        $this->totalOrder = $totalOrder;

        return $this;
    }

    public function getPendingOrder(): ?int
    {
        return $this->pendingOrder;
    }

    public function setPendingOrder(int $pendingOrder): self
    {
        $this->pendingOrder = $pendingOrder;

        return $this;
    }

    public function getOnHoldOrder(): ?int
    {
        return $this->onHoldOrder;
    }

    public function setOnHoldOrder(int $onHoldOrder): self
    {
        $this->onHoldOrder = $onHoldOrder;

        return $this;
    }

    public function getPaymentPendingOrder(): ?int
    {
        return $this->paymentPendingOrder;
    }

    public function setPaymentPendingOrder(int $paymentPendingOrder): self
    {
        $this->paymentPendingOrder = $paymentPendingOrder;

        return $this;
    }

    public function getShippedOrder(): ?int
    {
        return $this->shippedOrder;
    }

    public function setShippedOrder(int $shippedOrder): self
    {
        $this->shippedOrder = $shippedOrder;

        return $this;
    }

    public function getCanceledOrder(): ?int
    {
        return $this->canceledOrder;
    }

    public function setCanceledOrder(int $canceledOrder): self
    {
        $this->canceledOrder = $canceledOrder;

        return $this;
    }

    public function getCompletedOrder(): ?int
    {
        return $this->completedOrder;
    }

    public function setCompletedOrder(int $completedOrder): self
    {
        $this->completedOrder = $completedOrder;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Enum\OrderStateEnum;
use App\Repository\OrderRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $customerName;

    #[ORM\Column(type: 'string')]
    private $customerPhone;

    #[ORM\Column(type: 'string', length: 255)]
    private $address;

    #[ORM\Column(length: 255)]
    private ?OrderStateEnum $orderState = OrderStateEnum::Pending;

    #[ORM\Column(type: 'datetime_immutable')]
    private $placedAt;

    #[ORM\Column(type: 'integer')]
    private $totalCost;

    #[ORM\Column(type: 'text', nullable: true)]
    private $note;

    #[ORM\Column(type: 'boolean')]
    private $isPaid = false;

    #[ORM\OneToMany(mappedBy: 'productOrder', targetEntity: OrderItem::class, orphanRemoval: true, cascade: ['persist'])]
    private $orderItems;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $discount = 0;

    #[ORM\ManyToOne(targetEntity: ShippingMethod::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private $shippingMethod;

    #[ORM\ManyToOne(targetEntity: Customer::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: true)]
    private $customer;

    #[ORM\Column(type: 'boolean')]
    private $isUnique = true;

    #[ORM\Column(type: 'integer')]
    private $subTotal = 0;

    #[ORM\Column(type: 'integer')]
    private $deliveryCost = 0;

    #[ORM\Column(type: 'date', nullable: true)]
    private $orderDate;

    public function __construct()
    {
        $this->placedAt = new DateTimeImmutable();
        $this->orderDate = new DateTime();
        $this->orderItems = new ArrayCollection();
    }

    public function __toString()
    {
        return '#' . $this->id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomerName(): ?string
    {
        return $this->customerName;
    }

    public function setCustomerName(string $customerName): self
    {
        $this->customerName = $customerName;

        return $this;
    }

    public function getCustomerPhone(): ?string
    {
        return $this->customerPhone;
    }

    public function setCustomerPhone(string $customerPhone): self
    {
        $this->customerPhone = $customerPhone;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getOrderState(): ?OrderStateEnum
    {
        return $this->orderState;
    }

    public function setOrderState(OrderStateEnum $orderState): self
    {
        $this->orderState = $orderState;

        return $this;
    }

    public function getPlacedAt(): ?\DateTimeImmutable
    {
        return $this->placedAt;
    }

    public function setPlacedAt(\DateTimeImmutable $placedAt): self
    {
        $this->placedAt = $placedAt;

        return $this;
    }

    public function getTotalCost(): ?int
    {
        return $this->totalCost;
    }

    public function setTotalCost(int $totalCost): self
    {
        $this->totalCost = $totalCost;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function isIsPaid(): ?bool
    {
        return $this->isPaid;
    }

    public function setIsPaid(bool $isPaid): self
    {
        $this->isPaid = $isPaid;

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
            $orderItem->setProductOrder($this);
        }

        return $this;
    }

    public function removeOrderItem(OrderItem $orderItem): self
    {
        if ($this->orderItems->removeElement($orderItem)) {
            // set the owning side to null (unless already changed)
            if ($orderItem->getProductOrder() === $this) {
                $orderItem->setProductOrder(null);
            }
        }

        return $this;
    }

    public function getDiscount(): ?int
    {
        return $this->discount;
    }

    public function setDiscount(?int $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getShippingMethod(): ?ShippingMethod
    {
        return $this->shippingMethod;
    }

    public function setShippingMethod(?ShippingMethod $shippingMethod): self
    {
        $this->shippingMethod = $shippingMethod;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function isIsUnique(): ?bool
    {
        return $this->isUnique;
    }

    public function setIsUnique(bool $isUnique): self
    {
        $this->isUnique = $isUnique;

        return $this;
    }

    public function setStatusDummy($statusString)
    {
        $status = OrderStateEnum::from($statusString);

        $this->setOrderState($status);
    }
    public function GetStatusDummy()
    {
        return $this->getOrderState()->value;
    }

    public function getSubTotal(): ?int
    {
        return $this->subTotal;
    }

    public function setSubTotal(int $subTotal): self
    {
        $this->subTotal = $subTotal;

        return $this;
    }

    public function getDeliveryCost(): ?int
    {
        return $this->deliveryCost;
    }

    public function setDeliveryCost(int $deliveryCost): self
    {
        $this->deliveryCost = $deliveryCost;

        return $this;
    }

    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->orderDate;
    }

    public function setOrderDate(?\DateTimeInterface $orderDate): self
    {
        $this->orderDate = $orderDate;

        return $this;
    }
}

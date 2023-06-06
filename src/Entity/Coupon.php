<?php

namespace App\Entity;

use App\Repository\CouponRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CouponRepository::class)]
class Coupon
{
	#[ORM\Id]
	#[ORM\Column(length: 255, unique: true)]
	private ?string $code = null;

    #[ORM\ManyToOne(inversedBy: 'coupons')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CouponType $type = null;

    #[ORM\Column]
    private ?int $discount = null;

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getType(): ?CouponType
    {
        return $this->type;
    }

    public function setType(?CouponType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDiscount(): ?int
    {
        return $this->discount;
    }

    public function setDiscount(int $discount): self
    {
        $this->discount = $discount;

        return $this;
    }
}

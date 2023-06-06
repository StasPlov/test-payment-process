<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serialization;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
	#[Serialization\Groups('product')]
    private ?int $id = null;

	#[Serialization\Groups('product')]
	#[ORM\Column(type: 'datetime', nullable: true, columnDefinition: "TIMESTAMP DEFAULT CURRENT_TIMESTAMP")]
	private ?\DateTimeInterface $createdAt = null;

	#[Serialization\Groups('product')]
    #[ORM\Column(type: 'datetime', nullable: true, columnDefinition: "TIMESTAMP DEFAULT CURRENT_TIMESTAMP")]
    private ?\DateTimeInterface $updateAt = null;

    #[ORM\Column(length: 255)]
	#[Serialization\Groups('product')]
    private ?string $name = null;

    #[ORM\Column(length: 2000, nullable: true)]
	#[Serialization\Groups('product')]
    private ?string $description = null;

    #[ORM\Column]
	#[Serialization\Groups('product')]
    private ?float $price = null;

    #[ORM\Column]
    private ?bool $isDelete = null;

    #[ORM\Column]
    private ?bool $isHide = null;

    public function getId(): ?int
    {
        return $this->id;
    }

	public function getCreatedAt(): ?\DateTimeInterface
	{
		return $this->createdAt;
	}

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

	public function getUpdateAt(): ?\DateTimeInterface
	{
		return $this->updateAt;
	}

    public function setUpdateAt(?\DateTimeInterface $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function isIsDelete(): ?bool
    {
        return $this->isDelete;
    }

    public function setIsDelete(bool $isDelete): self
    {
        $this->isDelete = $isDelete;

        return $this;
    }

    public function isIsHide(): ?bool
    {
        return $this->isHide;
    }

    public function setIsHide(bool $isHide): self
    {
        $this->isHide = $isHide;

        return $this;
    }
}

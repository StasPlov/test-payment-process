<?php

namespace App\Entity;

use App\Repository\CountryTaxRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CountryTaxRepository::class)]
class CountryTax
{
	#[ORM\Id]
	#[ORM\ManyToOne(inversedBy: 'countryTaxes')]
	#[ORM\JoinColumn(nullable: false, referencedColumnName: 'code', unique: true)]
	private ?Country $country = null;

    #[ORM\Column]
    private ?float $tax = null;

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getTax(): ?float
    {
        return $this->tax;
    }

    public function setTax(float $tax): self
    {
        $this->tax = $tax;

        return $this;
    }
}

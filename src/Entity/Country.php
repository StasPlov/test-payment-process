<?php

namespace App\Entity;

use App\Repository\CountryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CountryRepository::class)]
class Country
{	
	#[ORM\Id]
	#[ORM\Column(length: 2, unique: true)]
	private ?string $code = null;

    #[ORM\Column(length: 1000)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'country', targetEntity: CountryTax::class)]
    private Collection $countryTaxes;

    public function __construct()
    {
        $this->countryTaxes = new ArrayCollection();
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

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

    /**
     * @return Collection<int, CountryTax>
     */
    public function getCountryTaxes(): Collection
    {
        return $this->countryTaxes;
    }

    public function addCountryTax(CountryTax $countryTax): self
    {
        if (!$this->countryTaxes->contains($countryTax)) {
            $this->countryTaxes->add($countryTax);
            $countryTax->setCountry($this);
        }

        return $this;
    }

    public function removeCountryTax(CountryTax $countryTax): self
    {
        if ($this->countryTaxes->removeElement($countryTax)) {
            // set the owning side to null (unless already changed)
            if ($countryTax->getCountry() === $this) {
                $countryTax->setCountry(null);
            }
        }

        return $this;
    }
}

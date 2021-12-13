<?php

namespace App\Entity;

use App\Repository\CountryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JetBrains\PhpStorm\Pure;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CountryRepository::class)
 */
class Country
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private ?string $name;

    /**
     * @ORM\ManyToOne(targetEntity=Locale::class, inversedBy="id")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(message="Locale not found. Please provide correct locale isoCode.")
     */

    private ?Locale $locale;

    /**
     * @ORM\ManyToMany(targetEntity=VatRate::class, mappedBy="country")
     */
    private Collection $vatRates;

    #[Pure] public function __construct()
    {
        $this->vatRates = new ArrayCollection();
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

    public function getLocale(): ?Locale
    {
        return $this->locale;
    }

    public function setLocale(?Locale $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return Collection|VatRate[]
     */
    public function getVatRates(): Collection
    {
        return $this->vatRates;
    }

    public function addVatRate(VatRate $vatRate): self
    {
        if (!$this->vatRates->contains($vatRate)) {
            $this->vatRates[] = $vatRate;
            $vatRate->addCountry($this);
        }

        return $this;
    }

    public function removeVatRate(VatRate $vatRate): self
    {
        if ($this->vatRates->removeElement($vatRate)) {
            $vatRate->removeCountry($this);
        }

        return $this;
    }
}

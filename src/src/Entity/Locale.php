<?php

namespace App\Entity;

use App\Repository\LocaleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=LocaleRepository::class)
 */
class Locale
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
     * @ORM\Column(type="string", length=10)
     * @Assert\Length(min=2, max=2, minMessage="Please provide correct locale", maxMessage="Please provide correct locale")
     */
    private ?string $isoCode;

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

    public function getIsoCode(): ?string
    {
        return $this->isoCode;
    }

    public function setIsoCode(string $isoCode): self
    {
        $this->isoCode = $isoCode;

        return $this;
    }
}

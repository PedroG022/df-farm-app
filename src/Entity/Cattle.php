<?php

namespace App\Entity;

use App\Repository\CattleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CattleRepository::class)]
class Cattle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    private ?string $code = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $milk_produced = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $feed = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $weight = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $birthdate = null;

    #[ORM\ManyToOne(inversedBy: 'cattle')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Farm $farm = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getMilkProduced(): ?int
    {
        return $this->milk_produced;
    }

    public function setMilkProduced(int $milk_produced): static
    {
        $this->milk_produced = $milk_produced;

        return $this;
    }

    public function getFeed(): ?int
    {
        return $this->feed;
    }

    public function setFeed(int $feed): static
    {
        $this->feed = $feed;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): static
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getFarm(): ?Farm
    {
        return $this->farm;
    }

    public function setFarm(?Farm $farm): static
    {
        $this->farm = $farm;

        return $this;
    }
}

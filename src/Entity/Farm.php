<?php

namespace App\Entity;

use App\Repository\FarmRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FarmRepository::class)]
class Farm
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(type: Types::FLOAT)]
    #[Assert\NotBlank]
    #[Assert\Positive]
    private ?float $hectares = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $responsible = null;

    #[ORM\OneToMany(targetEntity: Cattle::class, mappedBy: 'farm')]
    private Collection $cattle;

    #[ORM\ManyToMany(targetEntity: Veterinarian::class, inversedBy: 'farms')]
    private Collection $veterinarians;

    public function __construct()
    {
        $this->cattle = new ArrayCollection();
        $this->veterinarians = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getHectares(): ?float
    {
        return $this->hectares;
    }

    public function setHectares(float $hectares): static
    {
        $this->hectares = $hectares;

        return $this;
    }

    public function getResponsible(): ?string
    {
        return $this->responsible;
    }

    public function setResponsible(string $responsible): static
    {
        $this->responsible = $responsible;

        return $this;
    }

    /**
     * @return Collection<int, Cattle>
     */
    public function getCattle(): Collection
    {
        return $this->cattle;
    }

    public function addCattle(Cattle $cattle): static
    {
        if (!$this->cattle->contains($cattle)) {
            $this->cattle->add($cattle);
            $cattle->setFarm($this);
        }

        return $this;
    }

    public function removeCattle(Cattle $cattle): static
    {
        if ($this->cattle->removeElement($cattle)) {
            // set the owning side to null (unless already changed)
            if ($cattle->getFarm() === $this) {
                $cattle->setFarm(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Veterinarian>
     */
    public function getVeterinarians(): Collection
    {
        return $this->veterinarians;
    }

    public function addVeterinarian(Veterinarian $veterinarian): static
    {
        if (!$this->veterinarians->contains($veterinarian)) {
            $this->veterinarians->add($veterinarian);
        }

        return $this;
    }

    public function removeVeterinarian(Veterinarian $veterinarian): static
    {
        $this->veterinarians->removeElement($veterinarian);

        return $this;
    }
}

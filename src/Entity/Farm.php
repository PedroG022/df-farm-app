<?php

namespace App\Entity;

use App\Repository\FarmRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FarmRepository::class)]
class Farm
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $hectares = null;

    #[ORM\Column(length: 255)]
    private ?string $responsible = null;

    #[ORM\ManyToMany(targetEntity: Veterinarian::class, inversedBy: 'farms')]
    private Collection $veterinarians;

    #[ORM\OneToMany(targetEntity: Cattle::class, mappedBy: 'farm')]
    private Collection $cattle;

    public function __construct()
    {
        $this->veterinarians = new ArrayCollection();
        $this->cattle = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(string $id): static
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

    public function getHectares(): ?int
    {
        return $this->hectares;
    }

    public function setHectares(int $hectares): static
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
}

<?php

namespace App\Entity;

use App\Repository\VeterinarianRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VeterinarianRepository::class)]
class Veterinarian
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 64)]
    private ?string $crmv = null;

    #[ORM\ManyToMany(targetEntity: Farm::class, mappedBy: 'veterinarians')]
    private Collection $farms;

    public function __construct()
    {
        $this->farms = new ArrayCollection();
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

    public function getCrmv(): ?string
    {
        return $this->crmv;
    }

    public function setCrmv(string $crmv): static
    {
        $this->crmv = $crmv;

        return $this;
    }

    /**
     * @return Collection<int, Farm>
     */
    public function getFarms(): Collection
    {
        return $this->farms;
    }

    public function addFarm(Farm $farm): static
    {
        if (!$this->farms->contains($farm)) {
            $this->farms->add($farm);
            $farm->addVeterinarian($this);
        }

        return $this;
    }

    public function removeFarm(Farm $farm): static
    {
        if ($this->farms->removeElement($farm)) {
            $farm->removeVeterinarian($this);
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\MaterialRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MaterialRepository::class)]
class Material
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Hat>
     */
    #[ORM\OneToMany(targetEntity: Hat::class, mappedBy: 'material', orphanRemoval: true)]
    private Collection $hats;

    public function __construct()
    {
        $this->hats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, Hat>
     */
    public function getHats(): Collection
    {
        return $this->hats;
    }

    public function addHat(Hat $hat): static
    {
        if (!$this->hats->contains($hat)) {
            $this->hats->add($hat);
            $hat->setMaterial($this);
        }

        return $this;
    }

    public function removeHat(Hat $hat): static
    {
        if ($this->hats->removeElement($hat)) {
            // set the owning side to null (unless already changed)
            if ($hat->getMaterial() === $this) {
                $hat->setMaterial(null);
            }
        }

        return $this;
    }
}

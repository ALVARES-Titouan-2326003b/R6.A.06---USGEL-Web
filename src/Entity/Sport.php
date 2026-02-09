<?php

namespace App\Entity;

use App\Repository\SportRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SportRepository::class)]
class Sport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'sports')]
    #[ORM\JoinColumn(nullable: false)]
    private ?NomSport $nom = null;

    #[ORM\Column]
    private ?bool $equipe = null;

    /**
     * @var Collection<int, Championnat>
     */
    #[ORM\OneToMany(targetEntity: Championnat::class, mappedBy: 'sport', orphanRemoval: true)]
    private Collection $championnats;

    public function __construct()
    {
        $this->championnats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?NomSport
    {
        return $this->nom;
    }

    public function setNom(?NomSport $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function isEquipe(): ?bool
    {
        return $this->equipe;
    }

    public function setEquipe(bool $equipe): static
    {
        $this->equipe = $equipe;

        return $this;
    }

    /**
     * @return Collection<int, Championnat>
     */
    public function getChampionnats(): Collection
    {
        return $this->championnats;
    }

    public function addChampionnat(Championnat $championnat): static
    {
        if (!$this->championnats->contains($championnat)) {
            $this->championnats->add($championnat);
            $championnat->setSport($this);
        }

        return $this;
    }

    public function removeChampionnat(Championnat $championnat): static
    {
        if ($this->championnats->removeElement($championnat)) {
            // set the owning side to null (unless already changed)
            if ($championnat->getSport() === $this) {
                $championnat->setSport(null);
            }
        }

        return $this;
    }
}

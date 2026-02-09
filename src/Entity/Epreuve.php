<?php

namespace App\Entity;

use App\Repository\EpreuveRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EpreuveRepository::class)]
class Epreuve
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    /**
     * @var Collection<int, EpreuveCompetition>
     */
    #[ORM\OneToMany(targetEntity: EpreuveCompetition::class, mappedBy: 'epreuve', orphanRemoval: true)]
    private Collection $epreuveCompetitions;

    public function __construct()
    {
        $this->epreuveCompetitions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, EpreuveCompetition>
     */
    public function getEpreuveCompetitions(): Collection
    {
        return $this->epreuveCompetitions;
    }

    public function addEpreuveCompetition(EpreuveCompetition $epreuveCompetition): static
    {
        if (!$this->epreuveCompetitions->contains($epreuveCompetition)) {
            $this->epreuveCompetitions->add($epreuveCompetition);
            $epreuveCompetition->setEpreuve($this);
        }

        return $this;
    }

    public function removeEpreuveCompetition(EpreuveCompetition $epreuveCompetition): static
    {
        if ($this->epreuveCompetitions->removeElement($epreuveCompetition)) {
            // set the owning side to null (unless already changed)
            if ($epreuveCompetition->getEpreuve() === $this) {
                $epreuveCompetition->setEpreuve(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\EpreuveCompetitionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EpreuveCompetitionRepository::class)]
class EpreuveCompetition
{
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'epreuveCompetitions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Competition $competition = null;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'epreuveCompetitions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Epreuve $epreuve = null;

    public function getCompetition(): ?Competition
    {
        return $this->competition;
    }

    public function setCompetition(?Competition $competition): static
    {
        $this->competition = $competition;

        return $this;
    }

    public function getEpreuve(): ?Epreuve
    {
        return $this->epreuve;
    }

    public function setEpreuve(?Epreuve $epreuve): static
    {
        $this->epreuve = $epreuve;

        return $this;
    }
}

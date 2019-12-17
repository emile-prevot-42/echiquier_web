<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TurnRepository")
 */
class Turn
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Match", mappedBy="turn", orphanRemoval=true)
     */
    private $matches;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\tournament", inversedBy="turns")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tournament;

    public function __construct()
    {
        $this->matches = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Match[]
     */
    public function getMatches(): Collection
    {
        return $this->matches;
    }

    public function addMatch(Match $match): self
    {
        if (!$this->matches->contains($match)) {
            $this->matches[] = $match;
            $match->setTurn($this);
        }

        return $this;
    }

    public function removeMatch(Match $match): self
    {
        if ($this->matches->contains($match)) {
            $this->matches->removeElement($match);
            // set the owning side to null (unless already changed)
            if ($match->getTurn() === $this) {
                $match->setTurn(null);
            }
        }

        return $this;
    }

    public function getTournament(): ?tournament
    {
        return $this->tournament;
    }

    public function setTournament(?tournament $tournament): self
    {
        $this->tournament = $tournament;

        return $this;
    }
}

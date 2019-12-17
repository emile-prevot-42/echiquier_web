<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TeamRepository")
 */
class Team extends \App\Algo\Team
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * @ORM\Column(type="integer")
     */
    protected $rank;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tournament", inversedBy="teams")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tournament;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Match", mappedBy="teamHome", orphanRemoval=true)
     */
    private $matchesHome;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Match", mappedBy="teamAway", orphanRemoval=true)
     */
    private $matchesAway;

    public function __construct()
    {
        $this->matchesHome = new ArrayCollection();
        $this->matches = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
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

    public function getRank(): ?int
    {
        return $this->rank;
    }

    public function setRank(int $rank): self
    {
        $this->rank = $rank;

        return $this;
    }

    public function getTournament(): ?Tournament
    {
        return $this->tournament;
    }

    public function setTournament(?Tournament $tournament): self
    {
        $this->tournament = $tournament;

        return $this;
    }

    /**
     * @return Collection|Match[]
     */
    public function getMatchesHome(): Collection
    {
        return $this->matchesHome;
    }

    public function addMatchesHome(Match $matchesHome): self
    {
        if (!$this->matchesHome->contains($matchesHome)) {
            $this->matchesHome[] = $matchesHome;
            $matchesHome->setTeamHome($this);
        }

        return $this;
    }

    public function removeMatchesHome(Match $matchesHome): self
    {
        if ($this->matchesHome->contains($matchesHome)) {
            $this->matchesHome->removeElement($matchesHome);
            // set the owning side to null (unless already changed)
            if ($matchesHome->getTeamHome() === $this) {
                $matchesHome->setTeamHome(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Match[]
     */
    public function getMatchesAway(): Collection
    {
        return $this->matchesAway;
    }

    public function addMatchAway(Match $match): self
    {
        if (!$this->matchesAway->contains($match)) {
            $this->matchesAway[] = $match;
            $match->setTeamAway($this);
        }

        return $this;
    }

    public function removeMatchesAway(Match $match): self
    {
        if ($this->matchesAway->contains($match)) {
            $this->matchesAway->removeElement($match);
            // set the owning side to null (unless already changed)
            if ($match->getTeamAway() === $this) {
                $match->setTeamAway(null);
            }
        }

        return $this;
    }

}

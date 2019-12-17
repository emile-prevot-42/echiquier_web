<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MatchRepository")
 */
class Match
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\tournament", inversedBy="matches")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tournament;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team", inversedBy="matchesHome")
     * @ORM\JoinColumn(nullable=true)
     */
    private $teamHome;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team", inversedBy="matchesAway")
     * @ORM\JoinColumn(nullable=true)
     */
    private $teamAway;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\turn", inversedBy="matches")
     * @ORM\JoinColumn(nullable=false)
     */
    private $turn;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTeamHome(): ?team
    {
        return $this->teamHome;
    }

    public function setTeamHome(?team $teamHome): self
    {
        $this->teamHome = $teamHome;

        return $this;
    }

    public function getTeamAway(): ?Team
    {
        return $this->teamAway;
    }

    public function setTeamAway(?Team $teamAway): self
    {
        $this->teamAway = $teamAway;

        return $this;
    }

    public function getTurn(): ?turn
    {
        return $this->turn;
    }

    public function setTurn(?turn $turn): self
    {
        $this->turn = $turn;

        return $this;
    }
}

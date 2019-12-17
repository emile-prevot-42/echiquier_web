<?php


namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;


class Teams
{
    protected $teams;

    /**
     * @Assert\IsTrue(message="Il faut un nombre d’équipe pair.")
     */
    public function isPasswordSafe()
    {
        return 0 === (count($this->teams) % 2);
    }

    public function __construct()
    {
        $this->teams = new ArrayCollection();
    }

    public function getTeams()
    {
        return $this->teams;
    }

    public function setTeams( $teams)
    {
        $this->teams = $teams;
    }
}
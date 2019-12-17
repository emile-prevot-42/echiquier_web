<?php

namespace App\Algo;

class Team
{
    protected $name;

    private $possibleRivals;

    protected $rank;

    private $lock;

    public function __construct()
    {
        $this->possibleRivals = [];
        $this->lock = false;
    }

    public function addPossibleRival(Team $rival) : Team
    {
        $this->possibleRivals[] = $rival;
        return $this;
    }

    public function getPossibleRivals()
    {
        return $this->possibleRivals;
    }

    public function removePossibleRival(Team $rival) : Team
    {
        $newtab = [];
        foreach ($this->possibleRivals as $r)
        {
            if ($r !== $rival)
            {
                $newtab[] = $r;
            }
        }
        $this->possibleRivals = $newtab;
        return $this;
    }

    public function getNearRival()
    {
        $min = null;
        foreach ($this->possibleRivals as $rival)
        {
            if (!$rival->isLock())
            {
                if (null === $min)
                    $min = $rival;
                else
                {
                    $rank = $this->getRank();
                    $beforeDistance = abs($rank - $min->getRank());
                    $afterDistance = abs($rank - $rival->getRank());
                    $min = ($afterDistance < $beforeDistance) ? $rival : $min;
                }
            }
        }

        if ($min == null)
        {
            $sentence = 'On est la team '.$this->getName().', les rivaux suivants sont locks :';
            foreach ($this->possibleRivals as $rival)
            {
                $sentence .= ' '.$rival->getName() .' ('.$rival->isLock().')';
            }
            throw new \LogicException($sentence);
        }

        return $min;
    }

    public function getNearRivalDistance()
    {
        $rank = $this->getRank();
        return abs($rank - $this->getNearRival()->getRank());
    }

    public function lock()
    {
        $this->setLock(true);
        $this->getNearRival()->setLock(true);
    }

    public function unlock()
    {
        $this->setLock(false);
    }

    public function setLock(bool $lock)
    {
        return $this->lock = $lock;
    }

    public function isLock()
    {
        return $this->lock;
    }
}

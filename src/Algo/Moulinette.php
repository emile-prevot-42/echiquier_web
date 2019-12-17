<?php


namespace App\Algo;

class Moulinette
{
    protected function cmp(Team $a, Team $b)
    {
        return $a->getNearRivalDistance() > $b->getNearRivalDistance();
    }

    protected function cmpRank(Team $a, Team $b)
    {
        return $a->getRank() > $b->getRank();
    }

    protected function get_matchs_try($tab)
    {
        $matchs = [];
        try {
            foreach ($tab as $team) {
                if (!$team->isLock()) {
                    $matchs[] = [$team, $team->getNearRival()];
                    $team->lock();
                }
            }
        } catch (\LogicException $e) {
            $matchs = false;
        }
        foreach ($tab as $team) {
            $team->unlock();
        }
        return $matchs;
    }

    public function getMatchsFromTeams(array $tab) : array
    {
        // Même moi je doute sur ces deux lignes
        usort($tab, array($this, 'cmpRank'));
        //usort($tab, "cmp");
        $i = 0;
        while ($i < 1000) {
            if ($val = $this->get_matchs_try($tab)) {
                return $val;
            }
            shuffle($tab);
            $i++;
        }
        $permutations = new \drupol\phpermutations\Generators\Permutations($tab, count($tab));
        foreach ($permutations->generator() as $permutation) {
            if ($val = $this->get_matchs_try($permutation)) {
                return $val;
            }
        }
        $debug = "Impossible de trouver une solution pour la manche suivante \n";
        foreach ($tab as $team) {
            $debug .= $team->getName().' avec un rank de '.$team->getRank();
            foreach ($team->getPossibleRivals() as $rival) {
                $debug .= "contre ".$rival->getName()." ";
            }
            $debug .= "\n";
        }
        throw new \LogicException($debug);
    }
}

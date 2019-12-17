<?php


namespace App\Controller;

use App\Algo\Moulinette;
use App\Entity\Match;
use App\Entity\Team;
use App\Entity\Teams;
use App\Entity\Tournament;
use App\Entity\Turn;
use App\Form\TeamsType;
use App\Form\TeamsUpdateType;
use App\Form\TeamType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TeamController extends AbstractController
{
    /**
     * @var Moulinette
     */
    private $moulinette;

    public function __construct(Moulinette $moulinette)
    {
        $this->moulinette = $moulinette;
    }

    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return Response
     */
    public function homepage(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $teams = new Teams();
        for ($i = 1 ; $i <= 8 ; $i++) {
            $teams->getTeams()->add((new Team())->setName('Équipe '.$i)->setRank($i));
        }
        $form = $this->createForm(TeamsType::class, $teams);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $tournament = new Tournament();
            $entityManager->persist($tournament);
            foreach ($teams->getTeams() as $team)
            {
                $team->setTournament($tournament);
                $entityManager->persist($team);
            }
            $entityManager->flush();

            /** On lance l’algo */
            foreach ($teams->getTeams() as $teamMain)
            {
                foreach ($teams->getTeams() as $teamDeus)
                {
                    if ($teamMain->getId() != $teamDeus->getId())
                    {
                        $teamMain->addPossibleRival($teamDeus);
                    }
                }
            }

            $results = $this->moulinette->getMatchsFromTeams($teams->getTeams()->toArray());
            $turn = new Turn();
            $turn->setTournament($tournament);
            $entityManager->persist($turn);
            foreach ($results as $result)
            {
                $match = new Match();
                $match->setTeamHome($result[0]);
                $match->setTeamAway($result[1]);
                $match->setTournament($tournament);
                $match->setTurn($turn);
                $entityManager->persist($match);
            }
            $entityManager->flush();

            return $this->redirectToRoute('show_tournament', ['id' => $tournament->getId()]);
        }
        return $this->render('homepage.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show/{id}", name="show_tournament")
     * @param Request $request
     * @return Response
     */
    public function showTournament(Request $request, Tournament $tournament)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $teams = new Teams();
        $teams->setTeams($tournament->getTeams());

        $form = $this->createForm(TeamsUpdateType::class, $teams);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($teams->getTeams() as $team)
            {
                $entityManager->persist($team); // il peut modifier l’ID mais on sen fou lol on va pas se faire pirater hein
            }
            $entityManager->flush();

            $this->getDoctrine()
                ->getRepository(Team::class)
                ->getEchiquierPossibleTeams($tournament);


            $results = $this->moulinette->getMatchsFromTeams($tournament->getTeams()->toArray());

            $turn = new Turn();
            $turn->setTournament($tournament);
            $entityManager->persist($turn);
            foreach ($results as $result)
            {
                $match = new Match();
                $match->setTeamHome($result[0]);
                $match->setTeamAway($result[1]);
                $match->setTournament($tournament);
                $match->setTurn($turn);
                $entityManager->persist($match);
                $tournament->addMatch($match);
                $turn->addMatch($match);
            }
            $entityManager->flush();
            return $this->redirectToRoute('show_tournament', ['id' => $tournament->getId()]);
        }

        return $this->render('showTournament.html.twig', [
            'tournament' => $tournament,
            'form' => $form->createView(),
        ]);
    }
}
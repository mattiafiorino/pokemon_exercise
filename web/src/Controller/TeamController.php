<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\TeamService;
use App\Service\PokemonService;
use App\Repository\TeamRepository;
use App\Repository\PokemonRepository;


class TeamController extends AbstractController
{
    /**
     * 
     * @Route("/team/create")
     * 
     */
    public function createTeam(): Response
    {
        $number = random_int(0, 100);
        
        return $this->render('team/create.html.twig', [
            'number' => $number,
        ]);
    }
    
    /**
     *
     * @Route("/team/list")
     *
     */
    public function listTeams(): Response
    {
        $number = random_int(0, 100);
        
        return $this->render('team/list.html.twig', [
            'number' => $number,
        ]);
    }
    
    /**
     *
     * @Route("/team/new")
     *
     */
    public function newTeam(TeamService $teamService): JsonResponse
    {
        $name = "team_".microtime();
        $team = $teamService->createNewTeam($name);
        
        $response = new JsonResponse($team->getId());
        return $response;
    }
    
    /**
     *
     * @Route("/team/add")
     *
     */
    public function addPokemon(PokemonService $pokemonService, TeamRepository $teamRepository): JsonResponse
    {
        $team = $teamRepository->find(1);
        $pokemon = $pokemonService->newPokemon($team);
        
        $response = new JsonResponse($pokemon->getName()); 
        return $response;
    }
    
    /**
     *
     * @Route("/team/remove")
     *
     */
    public function removePokemon(PokemonService $pokemonService, PokemonRepository $pokemonRepository): JsonResponse
    {
        $pokemon = $pokemonRepository->find(1);
        $pokemonService->removePokemon($pokemon);
        
        $response = new Response(true);
        return $response;
    }
    
    /**
     *
     * @Route("/team/get")
     *
     */
    public function getTeam(TeamService $teamService): JsonResponse
    {
        $teams = $teamService->getAllTeams();
        
        $response = new JsonResponse($teams);
        return $response;
    }
}
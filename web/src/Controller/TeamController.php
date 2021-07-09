<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\TeamService;
use App\Service\PokemonService;
use App\Service\TypeService;
use App\Repository\TeamRepository;
use App\Repository\PokemonRepository;
use App\Repository\TypeRepository;


class TeamController extends AbstractController
{
    /**
     * 
     * @Route("/team/create")
     * 
     */
    public function createTeam(): Response
    {
        return $this->render('team/create.html.twig');
    }
    
    /**
     *
     * @Route("/team/list")
     *
     */
    public function listTeams(TeamService $teamService, TypeService $typeService): Response
    {
        $teams = $teamService->getAllTeamsCached();
        $types = $typeService->getAllTypesCached();

        return $this->render('team/list.html.twig', [
            'team_list' => $teams,
            'type_list' => $types
        ]);
    }
    
    /**
     *
     * @Route("/team/{teamid}/update")
     *
     */
    public function updateTeam($teamid, TeamRepository $teamRepository): Response
    {
        $team = $teamRepository->find($teamid);
        
        return $this->render('team/create.html.twig', [
            'team' => $team
        ]);
    }
    
    /**
     *
     * @Route("/team/new")
     *
     */
    public function newTeam(Request $request, TeamService $teamService): JsonResponse
    {
        $name = $request->query->get('name');
        $pokemonList = json_decode($request->query->get('pokemon_list'));
        
        $team = $teamService->createNewTeam($name, $pokemonList->pokemon_list);
        
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
        $pokemon = $pokemonService->addPokemon($team);
        
        $response = new JsonResponse($pokemon->getName()); 
        return $response;
    }
    
    /**
     *
     * @Route("/team/{teamid}/delete")
     *
     */
    public function deleteTeam($teamid, TeamRepository $teamRepository, TeamService $teamService): JsonResponse
    {
        $team = $teamRepository->find($teamid);
        $teamService->deleteTeam($team);
        
        $response = new JsonResponse(true);
        return $response;
    }
    
    /**
     *
     * @Route("/team/get")
     *
     */
    public function getTeam(TeamService $teamService): JsonResponse
    {
        $teams = $teamService->getAllTeamsCached();
        
        $response = new JsonResponse($teams);
        return $response;
    }
    
    /**
     *
     * @Route("/team/update")
     *
     */
    public function modifyExistingTeam(Request $request, TeamRepository $teamRepository, TeamService $teamService): JsonResponse
    {
        $name = $request->query->get('name');
        $pokemonList = json_decode($request->query->get('pokemon_list'));
        $teamid = $request->query->get('id');

        $team = $teamRepository->find($teamid);
        
        $teamService->updateTeam($team, $name, $pokemonList->pokemon_list);
        
        $response = new JsonResponse();
        return $response;
    }
}
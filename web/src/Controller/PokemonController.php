<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\TeamService;
use App\Service\PokemonService;
use App\Repository\TeamRepository;
use App\Repository\PokemonRepository;


class PokemonController extends AbstractController
{        
    /**
     *
     * @Route("/pokemon/new")
     *
     */
    public function addPokemon(PokemonService $pokemonService, TeamRepository $teamRepository): JsonResponse
    {
        $pokemon = $pokemonService->newPokemon();
        
        $response = new JsonResponse($pokemon); 
        return $response;
    }
    
}
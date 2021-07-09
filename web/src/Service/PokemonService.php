<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PokemonService {
    
    
    private $client;
    private $em;
    
    public function __construct(HttpClientInterface $client, EntityManagerInterface $em)
    {
        $this->client = $client;
        $this->em = $em;
    }  
    
    public function newPokemon(): array
    {
        $result = array();
        $randPokemon = $this->getRandomPokemon();
        
        $result['id'] = $randPokemon['id'];
        $result['name'] = $randPokemon['name'];
        $result['baseExp'] = $randPokemon['base_experience'];
        $result['image'] = $randPokemon['sprites']['front_default'];
        $result['abilities'] = array();
        $result['types'] = array();
        foreach($randPokemon['abilities'] as $ability){
            $result['abilities'][] = $ability['ability']['name'];
        }
        foreach($randPokemon['types'] as $ability){
            $result['types'][] = $ability['type']['name'];
        }
        
        return $result;
    }
    
    public function removePokemon($pokemon): bool
    {
        try{
            $this->em->remove($pokemon);
            $this->em->flush();
            return true;
        }catch (Exception $e){
            return false;
        }
    }
    
    private function getRandomPokemon(): array
    {
        $randId = rand(1,898);
        $response = $this->client->request(
            'GET',
            'https://pokeapi.co/api/v2/pokemon/'.$randId.'/'
            );
        
        $statusCode = $response->getStatusCode();
        if($statusCode == 200){
            $pokemon = $response->toArray();
            return $pokemon;
        } else {
            return false;
        }
    }
}
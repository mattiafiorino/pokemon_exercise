<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\Pokemon;

class PokemonService {
    
    
    private $client;
    private $em;
    
    public function __construct(HttpClientInterface $client, EntityManagerInterface $em)
    {
        $this->client = $client;
        $this->em = $em;
    }  
    
    public function newPokemon($team): Pokemon
    {
        $randPokemon = $this->getRandomPokemon();
        
        $pokemon = new Pokemon();
        $pokemon->setName($randPokemon['name']);
        $pokemon->setBaseExp($randPokemon['base_experience']);
        
        $filename = __DIR__."/../../public/images/pokemon/".$randPokemon['id'].".png";
        if(!file_exists($filename)){
            file_put_contents($filename, file_get_contents($randPokemon['sprites']['front_default']));
        }
        
        $pokemon->setImagePath("http://local.project.com/images/pokemon/".basename($filename));
        $pokemon->setTeam($team);
        
        $this->em->persist($pokemon);
        $this->em->flush();
        
        return $pokemon;
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
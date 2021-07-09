<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\Team;
use App\Entity\Pokemon;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use App\Repository\TeamRepository;
use App\Repository\AbilityRepository;
use App\Repository\TypeRepository;
use App\Entity\Ability;
use App\Entity\Type;

class TeamService {
    
    private $client;
    private $em;
    private $cache;
    private $teamRepository;
    private $abilityRepository;
    private $typeRepository;
    
    public function __construct(HttpClientInterface $client, EntityManagerInterface $em, TeamRepository $teamRepository, TypeRepository $typeRepository, AbilityRepository $abilityRepository)
    {
        $this->client = $client;
        $this->em = $em;
        $this->pool = new FilesystemAdapter();
        $this->teamRepository = $teamRepository;
        $this->abilityRepository = $abilityRepository;
        $this->typeRepository = $typeRepository;
    }    
    
    public function createNewTeam($name, $pokemonList){
        $team = new Team();
        
        $team->setName($name);
        $team->setDateCreate(new \DateTime);
        
        $this->em->persist($team);
        $this->em->flush();
        
        $this->addPokemonListToTeam($team, $pokemonList);
        
        return $team;
    }   
    
    public function updateTeam(&$team, $name, $pokemonList){
        if($team->getName() != $name) $team->setName($name);
        $team->setDateModify(new \DateTime);
        
        $this->em->persist($team);
        $this->em->flush();
        
        if(count($pokemonList) > 0){
            $this->addPokemonListToTeam($team, $pokemonList);
        }
        
        $this->pool->delete('team_key');
        
        return $team;
    }
    
    public function deleteTeam($team){
        $this->em->remove($team);
        $this->em->flush();
        
        $this->pool->delete('team_key');
    }
    
    public function getAllTeamsCached(){
        $teams = $this->pool->get('team_key', function (ItemInterface $itemInt) {
            $itemInt->expiresAfter(30);
            $return = array();
            $allTeamsRetrieved = $this->teamRepository->findBy(array(), array('dateCreate' => 'DESC'));
            
            foreach($allTeamsRetrieved as $team){
                $item = array();
                $item['id'] = $team->getId();
                $item['name'] = $team->getName();
                $item['images'] = array();
                $item['types'] = array();
                $item['tot_exp'] = 0;
                foreach($team->getPokemon() as $pokemon){
                    $item['images'][] = $pokemon->getImagePath();
                    foreach($pokemon->getTypes() as $type){
                        if(!in_array($type->getName(), $item['types']))
                            $item['types'][] = $type->getName();
                    }
                    asort($item['types']);
                    $item['tot_exp'] += $pokemon->getBaseExp();
                }
                $return[] = $item;
            }
            
            return $return;
        });
        
        return $teams;
    }
    
    private function addPokemonListToTeam($team, $pokemonList){
        foreach($pokemonList as $pokemon){
            $pokemonObj = new Pokemon();
            $pokemonObj->setName($pokemon->name);
            $pokemonObj->setBaseExp($pokemon->baseExp);
            $pokemonObj->setImagePath($pokemon->image);
            $pokemonObj->setTeam($team);
            
            $this->em->persist($pokemonObj);
            $this->em->flush();
            
            foreach($pokemon->abilities as $ability){
                $abilityObj = $this->abilityRepository->findOneBy(['name' => $ability]);
                if($abilityObj == null){
                    $abilityObj = new Ability();
                    $abilityObj->setName($ability);
                }
                $abilityObj->addPokemon($pokemonObj);
                
                $this->em->persist($abilityObj);
                $this->em->flush();
                
                $pokemonObj->addAbility($abilityObj);
            }
            
            foreach($pokemon->types as $type){
                $typeObj = $this->typeRepository->findOneBy(['name' => $type]);
                if($typeObj == null){
                    $typeObj = new Type();
                    $typeObj->setName($type);
                }
                $typeObj->addPokemon($pokemonObj);
                
                $this->em->persist($typeObj);
                $this->em->flush();
                
                $pokemonObj->addType($typeObj);
            }
            
            $this->em->persist($pokemonObj);
            $this->em->flush();
        }
    }
}
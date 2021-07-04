<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\Team;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use App\Repository\TeamRepository;

class TeamService {
    
    private $client;
    private $em;
    private $cache;
    private $teamRepository;
    
    public function __construct(HttpClientInterface $client, EntityManagerInterface $em, AdapterInterface $cache, TeamRepository $teamRepository)
    {
        $this->client = $client;
        $this->em = $em;
        $this->pool = $cache;
        $this->teamRepository = $teamRepository;
    }    
    
    public function createNewTeam($name){
        $team = new Team();
        
        $team->setName($name);
        $team->setDateCreate(new \DateTime);
        
        $this->em->persist($team);
        $this->em->flush();
        
        return $team;
    }   
    
    public function getAllTeams(){
        $teams = $this->pool->get('team_key', function (ItemInterface $item) {
            $item->expiresAfter(3600);
            
            $allTeams = $this->teamRepository->findAll();
            
            return $allTeams;
        });
        return $teams;
    }
}
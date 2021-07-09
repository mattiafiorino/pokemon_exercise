<?php
namespace App\Service;

use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use App\Repository\TypeRepository;

class TypeService {
    
    private $cache;
    private $typeRepository;
    
    public function __construct(TypeRepository $typeRepository)
    {
        $this->pool = new FilesystemAdapter();
        $this->typeRepository = $typeRepository;
    }    
    
    
    public function getAllTypesCached(){
        $types = $this->pool->get('type_key', function (ItemInterface $item) {
            $item->expiresAfter(30);

            $allTypesRetrieved = $this->typeRepository->findBy(array(), array('name' => 'DESC'));
            
            return $allTypesRetrieved;
        });
            return $types;
    }
}
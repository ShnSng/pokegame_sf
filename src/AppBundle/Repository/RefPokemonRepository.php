<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class RefPokemonRepository extends EntityRepository
{
    public function findStats()
    {
        $query = $this->getEntityManager()->createQuery('SELECT t FROM AppBundle:RefElementaryType t ORDER BY t.libelle');
        $iterable = $query->iterate();
        $stats = array();
        foreach($iterable as $row){
            $type = $row[0];
            $res = $this->getEntityManager()->createQuery('SELECT COUNT(p.id) FROM AppBundle:RefPokemon p WHERE p.type1 = ?1 OR p.type2 = ?1')
                ->setParameter(1, $type->getId())
                ->getSingleScalarResult();
            $stats[$type->getLibelle()] = $res;        
            $this->getEntityManager()->detach($type);
        }
        return $stats;
    }
}
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

    public function findRandomByPlace(string $place)
    {
        $place = $this->getEntityManager()->getRepository('AppBundle:RefPlace')->findOneById($place);
        $res = $this->getEntityManager()->createQuery('SELECT e FROM AppBundle:RefETypePlace e WHERE e.idPlace = ?1')
            ->setParameter(1, $place->getId())
            ->getResult();
        $type = rand(0, count($res) - 1);
        $type = $res[$type];
        $res = $this->getEntityManager()->createQuery('SELECT p FROM AppBundle:RefPokemon p WHERE p.type1 = ?1 OR p.type2 = ?1')
            ->setParameter(1, $type->getIdType())
            ->getResult();
        $pokemon = rand(0, count($res) - 1);
        $pokemon = $res[$pokemon];
        return $pokemon;
    }
}
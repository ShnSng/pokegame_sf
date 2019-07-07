<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RefETypePlace
 *
 * @ORM\Table(name="ref_e_type_place")
 * @ORM\Entity
 */
class RefETypePlace
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_type", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idType;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_place", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idPlace;



    /**
     * Get the value of idType
     *
     * @return  integer
     */ 
    public function getIdType()
    {
        return $this->idType;
    }

    /**
     * Set the value of idType
     *
     * @param  integer  $idType
     *
     * @return  self
     */ 
    public function setIdType($idType)
    {
        $this->idType = $idType;

        return $this;
    }

    /**
     * Get the value of idPlace
     *
     * @return  integer
     */ 
    public function getIdPlace()
    {
        return $this->idPlace;
    }

    /**
     * Set the value of idPlace
     *
     * @param  integer  $idPlace
     *
     * @return  self
     */ 
    public function setIdPlace($idPlace)
    {
        $this->idPlace = $idPlace;

        return $this;
    }
}


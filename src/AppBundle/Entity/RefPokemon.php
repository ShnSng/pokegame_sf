<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RefPokemon
 *
 * @ORM\Table(name="ref_pokemon")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RefPokemonRepository")
 */
class RefPokemon
{
    /**
     * @var boolean
     *
     * @ORM\Column(name="evolution", type="boolean", nullable=false)
     */
    private $evolution;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=50, nullable=false)
     */
    private $nom;

    /**
     * @var boolean
     *
     * @ORM\Column(name="starter", type="boolean", nullable=false)
     */
    private $starter;

    /**
     * @ORM\OneToOne(targetEntity="RefElementaryType")
     * @ORM\JoinColumn(name="type_1", referencedColumnName="id")
     */
    private $type1;

    /**
     * @ORM\OneToOne(targetEntity="RefElementaryType")
     * @ORM\JoinColumn(name="type_2", referencedColumnName="id")
     */
    private $type2;

    /**
     * @var string
     *
     * @ORM\Column(name="type_courbe_niveau", type="string", length=1, nullable=false)
     */
    private $typeCourbeNiveau;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Get the value of evolution
     *
     * @return  bool
     */ 
    public function getEvolution()
    {
        return $this->evolution;
    }

    /**
     * Set the value of evolution
     *
     * @param  bool  $evolution
     *
     * @return  self
     */ 
    public function setEvolution(bool $evolution)
    {
        $this->evolution = $evolution;

        return $this;
    }

    /**
     * Get the value of nom
     *
     * @return  string
     */ 
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set the value of nom
     *
     * @param  string  $nom
     *
     * @return  self
     */ 
    public function setNom(string $nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get the value of starter
     *
     * @return  bool
     */ 
    public function getStarter()
    {
        return $this->starter;
    }

    /**
     * Set the value of starter
     *
     * @param  bool  $starter
     *
     * @return  self
     */ 
    public function setStarter(bool $starter)
    {
        $this->starter = $starter;

        return $this;
    }

    /**
     * Get the value of type1
     *
     * @return  integer
     */ 
    public function getType1()
    {
        return $this->type1;
    }

    /**
     * Set the value of type1
     *
     * @param  integer  $type1
     *
     * @return  self
     */ 
    public function setType1($type1)
    {
        $this->type1 = $type1;

        return $this;
    }

    /**
     * Get the value of type2
     *
     * @return  integer
     */ 
    public function getType2()
    {
        return $this->type2;
    }

    /**
     * Set the value of type2
     *
     * @param  integer  $type2
     *
     * @return  self
     */ 
    public function setType2($type2)
    {
        $this->type2 = $type2;

        return $this;
    }

    /**
     * Get the value of typeCourbeNiveau
     *
     * @return  string
     */ 
    public function getTypeCourbeNiveau()
    {
        return $this->typeCourbeNiveau;
    }

    /**
     * Set the value of typeCourbeNiveau
     *
     * @param  string  $typeCourbeNiveau
     *
     * @return  self
     */ 
    public function setTypeCourbeNiveau(string $typeCourbeNiveau)
    {
        $this->typeCourbeNiveau = $typeCourbeNiveau;

        return $this;
    }

    /**
     * Get the value of id
     *
     * @return  integer
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param  integer  $id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}


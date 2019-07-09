<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pokemon
 *
 * @ORM\Table(name="pokemon", indexes={@ORM\Index(name="FK_62DC90F3A926F002", columns={"ref_pokemon_id"})})
 * @ORM\Entity
 */
class Pokemon
{
    /**
     * @var boolean
     *
     * @ORM\Column(name="a_vendre", type="boolean", nullable=false)
     */
    private $aVendre;

    /**
     * @var integer
     *
     * @ORM\Column(name="date_dernier_entrainement", type="integer", nullable=false)
     */
    private $dateDernierEntrainement;

    /**
     * @ORM\OneToOne(targetEntity="Trainer")
     * @ORM\JoinColumn(name="dresseur_id", referencedColumnName="id")
     */
    private $dresseur;

    /**
     * @var integer
     *
     * @ORM\Column(name="niveau", type="integer", nullable=false)
     */
    private $niveau;

    /**
     * @var integer
     *
     * @ORM\Column(name="prix", type="integer", nullable=false)
     */
    private $prix;

    /**
     * @ORM\OneToOne(targetEntity="RefPokemon")
     * @ORM\JoinColumn(name="ref_pokemon_id", referencedColumnName="id")
     */
    private $refPokemon;

    /**
     * @var string
     *
     * @ORM\Column(name="sexe", type="string", length=1, nullable=false)
     */
    private $sexe;

    /**
     * @var integer
     *
     * @ORM\Column(name="xp", type="integer", nullable=false)
     */
    private $xp;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Get the value of aVendre
     *
     * @return  bool
     */ 
    public function getAVendre()
    {
        return $this->aVendre;
    }

    /**
     * Set the value of aVendre
     *
     * @param  bool  $aVendre
     *
     * @return  self
     */ 
    public function setAVendre($aVendre)
    {
        $this->aVendre = $aVendre;

        return $this;
    }

    /**
     * Get the value of dateDernierEntrainement
     *
     * @return  integer
     */ 
    public function getDateDernierEntrainement()
    {
        return $this->dateDernierEntrainement;
    }

    /**
     * Set the value of dateDernierEntrainement
     *
     * @param  integer  $dateDernierEntrainement
     *
     * @return  self
     */ 
    public function setDateDernierEntrainement($dateDernierEntrainement)
    {
        $this->dateDernierEntrainement = $dateDernierEntrainement;

        return $this;
    }

    /**
     * Get the value of dresseur
     *
     * @return  integer
     */ 
    public function getDresseur()
    {
        return $this->dresseur;
    }

    /**
     * Set the value of dresseur
     *
     * @param  integer  $dresseur
     *
     * @return  self
     */ 
    public function setDresseur($dresseur)
    {
        $this->dresseur = $dresseur;

        return $this;
    }

    /**
     * Get the value of niveau
     *
     * @return  integer
     */ 
    public function getNiveau()
    {
        return $this->niveau;
    }

    /**
     * Set the value of niveau
     *
     * @param  integer  $niveau
     *
     * @return  self
     */ 
    public function setNiveau($niveau)
    {
        $this->niveau = $niveau;

        return $this;
    }

    /**
     * Get the value of prix
     *
     * @return  integer
     */ 
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set the value of prix
     *
     * @param  integer  $prix
     *
     * @return  self
     */ 
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get the value of refPokemon
     *
     * @return  integer
     */ 
    public function getRefPokemon()
    {
        return $this->refPokemon;
    }

    /**
     * Set the value of refPokemon
     *
     * @param  integer  $refPokemon
     *
     * @return  self
     */ 
    public function setRefPokemon($refPokemon)
    {
        $this->refPokemon = $refPokemon;

        return $this;
    }

    /**
     * Get the value of sexe
     *
     * @return  string
     */ 
    public function getSexe()
    {
        return $this->sexe;
    }

    /**
     * Set the value of sexe
     *
     * @param  string  $sexe
     *
     * @return  self
     */ 
    public function setSexe($sexe)
    {
        $this->sexe = $sexe;

        return $this;
    }

    /**
     * Get the value of xp
     *
     * @return  integer
     */ 
    public function getXp()
    {
        return $this->xp;
    }

    /**
     * Set the value of xp
     *
     * @param  integer  $xp
     *
     * @return  self
     */ 
    public function setXp($xp)
    {
        $this->xp = $xp;

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


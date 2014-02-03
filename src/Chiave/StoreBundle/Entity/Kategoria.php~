<?php

namespace Chiave\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Kategoria
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Chiave\StoreBundle\Entity\KategoriaRepository")
 */
class Kategoria
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $name;
    
    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $photo_link;
    
    /**
     * @ORM\Column(type="text")
     */
    protected $description;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Kategoria
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set photo_link
     *
     * @param string $photoLink
     * @return Kategoria
     */
    public function setPhotoLink($photoLink)
    {
        $this->photo_link = $photoLink;

        return $this;
    }

    /**
     * Get photo_link
     *
     * @return string 
     */
    public function getPhotoLink()
    {
        return $this->photo_link;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Kategoria
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }
}

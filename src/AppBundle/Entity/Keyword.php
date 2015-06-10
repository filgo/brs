<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Metier
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Keyword
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MetierKeyword", mappedBy="keyword")
     */
    protected $metier_keyword;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->metier_keyword = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * @return Keyword
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
     * Add metier_keyword
     *
     * @param \AppBundle\Entity\MetierKeyword $metierKeyword
     * @return Keyword
     */
    public function addMetierKeyword(\AppBundle\Entity\MetierKeyword $metierKeyword)
    {
        $this->metier_keyword[] = $metierKeyword;

        return $this;
    }

    /**
     * Remove metier_keyword
     *
     * @param \AppBundle\Entity\MetierKeyword $metierKeyword
     */
    public function removeMetierKeyword(\AppBundle\Entity\MetierKeyword $metierKeyword)
    {
        $this->metier_keyword->removeElement($metierKeyword);
    }

    /**
     * Get metier_keyword
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMetierKeyword()
    {
        return $this->metier_keyword;
    }
}

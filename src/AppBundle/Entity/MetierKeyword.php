<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Metier
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class MetierKeyword
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Keyword", inversedBy="metier_keyword")
     * @ORM\JoinColumn(name="fk_keyword_id", referencedColumnName="id")
     */
    protected $keyword;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Metier", inversedBy="metier_keyword")
     * @ORM\JoinColumn(name="fk_metier_id", referencedColumnName="id")
     */
    protected $metier;

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
     * Set keyword
     *
     * @param \AppBundle\Entity\Keyword $keyword
     * @return MetierKeyword
     */
    public function setKeyword(\AppBundle\Entity\Keyword $keyword = null)
    {
        $this->keyword = $keyword;

        return $this;
    }

    /**
     * Get keyword
     *
     * @return \AppBundle\Entity\Keyword 
     */
    public function getKeyword()
    {
        return $this->keyword;
    }

    /**
     * Set metier
     *
     * @param \AppBundle\Entity\Metier $metier
     * @return MetierKeyword
     */
    public function setMetier(\AppBundle\Entity\Metier $metier = null)
    {
        $this->metier = $metier;

        return $this;
    }

    /**
     * Get metier
     *
     * @return \AppBundle\Entity\Metier 
     */
    public function getMetier()
    {
        return $this->metier;
    }
}

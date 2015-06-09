<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Metier
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Metier
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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CompanyMetier", mappedBy="metier")
     */
    protected $company_metier;

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
     * @return Metier
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
     * Constructor
     */
    public function __construct()
    {
        $this->company_metier = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add company_metier
     *
     * @param \AppBundle\Entity\CompanyMetier $companyMetier
     * @return Metier
     */
    public function addCompanyMetier(\AppBundle\Entity\CompanyMetier $companyMetier)
    {
        $this->company_metier[] = $companyMetier;

        return $this;
    }

    /**
     * Remove company_metier
     *
     * @param \AppBundle\Entity\CompanyMetier $companyMetier
     */
    public function removeCompanyMetier(\AppBundle\Entity\CompanyMetier $companyMetier)
    {
        $this->company_metier->removeElement($companyMetier);
    }

    /**
     * Get company_metier
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCompanyMetier()
    {
        return $this->company_metier;
    }
}

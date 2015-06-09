<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="company")
 */
class Company
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=300)
     */
    private $site_address;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $phone;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\City", inversedBy="companies")
     * @ORM\JoinColumn(name="fk_city_id", referencedColumnName="id")
     */
    protected $city;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CompanyMetier", mappedBy="company")
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
     * @return Company
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
     * Set address
     *
     * @param string $address
     * @return Company
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set city
     *
     * @param \AppBundle\Entity\City $city
     * @return Company
     */
    public function setCity(\AppBundle\Entity\City $city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return \AppBundle\Entity\City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Company
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
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
     * @return Company
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

    /**
     * Set site_address
     *
     * @param string $siteAddress
     * @return Company
     */
    public function setSiteAddress($siteAddress)
    {
        $this->site_address = $siteAddress;

        return $this;
    }

    /**
     * Get site_address
     *
     * @return string 
     */
    public function getSiteAddress()
    {
        return $this->site_address;
    }
}

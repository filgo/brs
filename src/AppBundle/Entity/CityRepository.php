<?php
namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class CityRepository extends EntityRepository
{
    public function findAllByName($sName, $aOptions)
    {
        return $this
            ->createQueryBuilder('c')
            ->where("c.name like :name")
            ->setParameter('name', '%'.$sName.'%')
            ->orderBy('c.name', 'ASC')
            ->setMaxResults($aOptions['limit'])
            ->getQuery()
            ->getResult();
    }
}
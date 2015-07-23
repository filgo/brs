<?php
namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class CityRepository extends EntityRepository
{
    public function findAllByName($sName, $aOptions)
    {
      $query = $this
            ->createQueryBuilder('c')
            ->where("c.name like :name")
            ->setParameter('name', '%'.$sName.'%')
            ->orderBy('c.name', 'ASC');

        if(isset($aOptions['limit']))
        {
          $query->setMaxResults($aOptions['limit']);
        }

        return $query

            ->getQuery()
            ->getResult();
    }

    public function findByRadius($latitude, $longitude, $radius)
    {
      $formuleDistance ="GEO(c.latitude = :latitude, c.longitude = :longitude)";

      return $this
      ->createQueryBuilder('c')
      ->where("$formuleDistance < :radius")
      ->setParameter('latitude', $latitude)
      ->setParameter('longitude', $longitude)
      ->setParameter('radius', $radius)
      ->getQuery()
      ->getResult();
    }

}
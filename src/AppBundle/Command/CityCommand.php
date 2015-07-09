<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Entity\Company;
use AppBundle\Entity\City;
use AppBundle\Entity\CompanyMetier;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class CityCommand extends ContainerAwareCommand
{
  protected function configure()
  {
    $this->setName('import:city')->setDescription('Import cities');
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $handle = fopen('web/uploads/pt_postal_codes.csv', 'r');

    $oEntityManager = $this->getContainer()->get('doctrine')->getEntityManager();

    while (($data = fgetcsv($handle, 5000, ",")) !== false)
    {
      $output->writeln($data);

      $zipCode = explode('-',$data[0]);

      $query = $oEntityManager->getRepository('AppBundle:City')->createQueryBuilder('c')
        ->where('c.postalCode = :cp')
        ->andwhere('c.name = :name')
        ->setParameter('cp', $zipCode[0])
        ->setParameter('name', $data[4])
        ->getQuery();

      $oCity = $query->getOneOrNullResult();

      if (! $oCity instanceof City)
      {
        $oCity = new City();
        $oCity->setName($data[4]);
        $oCity->setPostalCode($zipCode[0]);
        $oCity->setLatitude($data[5]);
        $oCity->setLongitude($data[6]);
      }

      $oEntityManager->persist($oCity);
      $oEntityManager->flush();
    }


  }
}
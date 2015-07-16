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

class SnifCommand extends ContainerAwareCommand
{
  protected function configure()
  {
    $this->setName('import:snif')->setDescription('Import firm');
  }
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $shost = 'pai';

    for($page = 1;; $page ++)
    {
      $sURL = 'http://www.' . $shost .'.pt/q/ajax/business?contentErrorLinkEnabled=true&input=Pintores%20de%20Constru%C3%A7%C3%A3o%20Civil&what=Pintores%20de%20Constru%C3%A7%C3%A3o%20Civil&where=&type=DOUBLE&resultlisttype=A_AND_B&page='.$page;

      $output->writeln($sURL);

      $oCurl = curl_init();
      curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($oCurl, CURLOPT_HEADER, 1);
      curl_setopt($oCurl, CURLOPT_URL, $sURL);
      // curl_setopt($oCurl, CURLOPT_POST, 2);
      // curl_setopt($oCurl, CURLOPT_POSTFIELDS, 'user=qpro&password=AAIUaI');

      $resultat = curl_exec($oCurl);
      curl_close($oCurl);

      $body = $this->getResult($resultat);

      $encoders = array(
          new JsonEncoder()
      );
      $normalizers = array(
          new GetSetMethodNormalizer()
      );

      $serializer = new Serializer($normalizers, $encoders);

      $aDeserialized = $serializer->decode($body, 'json');

      $oEntityManager = $this->getContainer()->get('doctrine')->getEntityManager();

      $oMetier = $oEntityManager->getRepository('AppBundle:Metier')->findOneByName('pintor');

      if(!$aDeserialized['data']['flyouts'])
      {
        break;
      }

      foreach ( $aDeserialized['data']['flyouts'] as $iIndex => $aInfos )
      {
        $sAddress = '';
        $sCp = '';
        $sCityName = '';

        if($aInfos['address'] != '')
        {
          $aAddressExp = explode("<br/>", $aInfos['address']);

          preg_match_all('/([0-9][0-9][0-9][0-9]\-[0-9][0-9][0-9])(.*)/', $aAddressExp[1], $aAddressDetail);

          $sAddress = $aAddressExp[0];
          if($aAddressDetail[1])
          {
            $sCp = $aAddressDetail[1][0];
            $aCp = explode('-', $sCp);
            $sCpCity = $aCp[0];
            $sCpCompany = $sCp;

            $sCityName = substr($aAddressDetail[2][0], 1);
          }
        }

        $query = $oEntityManager->getRepository('AppBundle:City')->createQueryBuilder('c')
        ->where('c.postalCode = :cp')
        ->andwhere('collate(c.name, utf8_bin) = :name')
        ->setParameter('cp', $sCpCity)
        ->setParameter('name', $sCityName)
        ->getQuery();

        $oCity = $query->getOneOrNullResult();


        if (! $oCity instanceof City && $sCityName != '')
        {
          $oCity = new City();
          $oCity->setName($sCityName);
          $oCity->setPostalCode($sCpCity);
          $oCity->setLatitude(0);
          $oCity->setLongitude(0);
          $oEntityManager->persist($oCity);
        }

        $oCompany = new Company();
        $oCompany->setName($aInfos['name']);
        $oCompany->setAddress($sAddress);
        $oCompany->setCity($oCity);
        $oCompany->setCp($sCpCompany);
        $oCompany->setPhone(\str_replace(" ", "", $aInfos['contactDetails']['phone']));
        $oCompany->setMobile(\str_replace(" ", "", $aInfos['contactDetails']['mobile']));
        $oCompany->setFax(\str_replace(" ", "", $aInfos['contactDetails']['fax']));
        $oCompany->setSiteAddress($aInfos["website"]["url"]);
        $oCompany->setLatitude($aInfos['coordinate']['x']);
        $oCompany->setLongitude($aInfos['coordinate']['y']);
        $oEntityManager->persist($oCompany);

        $oCompanyMetier = new CompanyMetier();
        $oCompanyMetier->setCompany($oCompany);
        $oCompanyMetier->setMetier($oMetier);
        $oEntityManager->persist($oCompanyMetier);

        $oEntityManager->flush();

        $output->writeln('Add :' . $aInfos['name']);
      }
    }
  }
  private function getResult($resultat)
  {
    list ($header, $body) = explode("\r\n\r\n", $resultat, 2);

    return $body;
  }
}
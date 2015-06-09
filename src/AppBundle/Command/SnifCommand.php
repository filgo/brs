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

class SnifCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('import:snif')
            ->setDescription('Import firm');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
      $shost = 'pai';

      $sURL = 'http://www.'.$shost.'.pt/q/business/advanced/what/pintor/?contentErrorLinkEnabled=true';
      $oCurl = curl_init();
      curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($oCurl, CURLOPT_HEADER, 1);
      curl_setopt($oCurl, CURLOPT_URL, $sURL);
      //curl_setopt($oCurl, CURLOPT_POST, 2);
      //curl_setopt($oCurl, CURLOPT_POSTFIELDS, 'user=qpro&password=AAIUaI');

      $resultat = curl_exec ($oCurl);
      curl_close($oCurl);
      $body = $this->getResult($resultat);

      preg_match_all('/<span id="listingbase[0-9]*" class="result-bn medium"> (.*?)<\/span>/s', $body, $aNames);
      preg_match_all('/<div class="result-address">(.*?)<\/div>/s', $body, $aAddress);
      preg_match_all('/<span class="phone-number">(.*?)<\/span>/s', $body, $aPhones);

      $oEntityManager = $this->getContainer()->get('doctrine')->getEntityManager();

      $oMetier = $oEntityManager->getRepository('AppBundle:Metier')->findOneByName('pintor');

      foreach ($aNames[1] as $iIndex => $sName) {

        $aAddressExp = explode("<br/>", $aAddress[1][$iIndex]);

        preg_match_all('/([0-9][0-9][0-9][0-9]\-[0-9][0-9][0-9])(.*)/', $aAddressExp[1], $aAddressDetail);

        $sCp = $aAddressDetail[1][0];
        $sCityName = substr($aAddressDetail[2][0], 1);

        $oCity = $oEntityManager->getRepository('AppBundle:City')->findOneByPostalCode($sCp);

        if(! $oCity instanceof City)
        {
          $oCity = new City();
          $oCity->setName($sCityName);
          $oCity->setPostalCode($sCp);
          $oEntityManager->persist($oCity);
        }

        $oCompany = new Company();
        $oCompany->setName($sName);
        $oCompany->setAddress($aAddressExp[0]);
        $oCompany->setCity($oCity);
        $oCompany->setPhone(\str_replace(" ", "", $aPhones[1][$iIndex]));
        $oEntityManager->persist($oCompany);

        $oCompanyMetier = new CompanyMetier();
        $oCompanyMetier->setCompany($oCompany);
        $oCompanyMetier->setMetier($oMetier);
        $oEntityManager->persist($oCompanyMetier);

        $oEntityManager->flush();
      }

      $output->writeln('done');
    }

    private function getResult($resultat)
    {
      list($header, $body) = explode("\r\n\r\n", $resultat, 2);

      return $body;
    }
}
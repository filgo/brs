<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Entity\Company;

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

      $oEntityManager = $this->getContainer()->get('doctrine')->getEntityManager();

      foreach ($aNames[1] as $iIndex => $sName) {

        $sAddress = \str_replace("<br/>", "", $aAddress[1][$iIndex]);

        $oCompany = new Company();
        $oCompany->setName($sName);
        $oCompany->setAddress($sAddress);
        $oEntityManager->persist($oCompany);
        $oEntityManager->flush();
      }

      $output->writeln('');
    }

    private function getResult($resultat)
    {
      list($header, $body) = explode("\r\n\r\n", $resultat, 2);

      return $body;
    }
}
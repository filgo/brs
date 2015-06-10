<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\AppBundle;

class CityController extends Controller
{
    /**
     * @Route("/app/example", name="homepage")
     */
    public function listAction()
    {
      $format = $this->getRequest()->getRequestFormat();

      $sCityName = $this->getRequest()->query->get('name');

      $aData = array();

      $oLCity = $this->getDoctrine()
        ->getEntityManager()
        ->getRepository('AppBundle:City')
        ->findAllByName($sCityName, array('limit' => 5));

      foreach ($oLCity as $oCity)
      {
        $aData[$oCity->getId()] = $oCity->getName().' ('.$oCity->getPostalCode().')';
      }

      return $this->render('AppBundle:City:list.'.$format.'.twig', array('data' => $aData));
    }
}

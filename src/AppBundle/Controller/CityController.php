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

      $aData = array();

      $oLCity = $this->getDoctrine()->getRepository('AppBundle:City')->findAll();

      foreach ($oLCity as $oCity)
      {
        $aData[$oCity->getId()] = $oCity->getName();
      }

      return $this->render('AppBundle:City:list.'.$format.'.twig', array('data' => $aData));
    }
}

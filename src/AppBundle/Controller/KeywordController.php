<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\AppBundle;

class KeywordController extends Controller
{
    /**
     * @Route("/app/example", name="homepage")
     */
    public function listAction()
    {
      $format = $this->getRequest()->getRequestFormat();

      $aData = array();

      $oLKeyword = $this->getDoctrine()->getRepository('AppBundle:Keyword')->findAll();

      foreach ($oLKeyword as $oKeyword)
      {
        $aData[$oKeyword->getId()] = $oKeyword->getName();
      }

      return $this->render('AppBundle:Keyword:list.'.$format.'.twig', array('data' => $aData));
    }
}

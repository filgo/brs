<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\AppBundle;

class MetierController extends Controller
{
  /**
   * @Route("/profissoes.{_format}")
   */
  public function listAction(Request $request)
  {
    $format = $request->getRequestFormat();

    $lMetier = $this->getDoctrine()
        ->getRepository('AppBundle:Metier')
        ->createQueryBuilder('c')
        ->getQuery()
        ->getResult();

    return $this->render('AppBundle:Metier:list.'.$format.'.twig', array('lMetier' => $lMetier));
  }
}
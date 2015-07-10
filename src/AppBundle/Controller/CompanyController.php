<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\AppBundle;

class CompanyController extends Controller
{
    /**
     * @Route("/companies-{city}-{postal_code}.{_format}")
     */
    public function listAction($keyword, $city, $postal_code, Request $request)
    {
      $format = $request->getRequestFormat();


      $oCity = $this->getDoctrine()->getRepository('AppBundle:City')->createQueryBuilder('c')
      ->where('c.postalCode = :cp')
      ->andwhere('collate(LOWER(c.name), utf8_bin) like :name')
      ->setParameter('cp', $postal_code)
      ->setParameter('name', $city)
      ->getQuery()
      ->getOneOrNullResult();

      $oLCity = $this->getDoctrine()
        ->getRepository('AppBundle:City')
        ->findByRadius($oCity->getLatitude(), $oCity->getLongitude(), '50');


        foreach ($oLCity as $oCity)
        {
          $aCities[] = $oCity->getId();
        }

      $oLCompany = $this->getDoctrine()
        ->getRepository('AppBundle:Company')
        ->createQueryBuilder('c')
        ->innerJoin('c.city', 'ci')
        ->where("ci.id IN(:citiesId)")
        ->setParameter('citiesId', $aCities)
        ->getQuery()
        ->getResult();



      return $this->render('AppBundle:Company:list.'.$format.'.twig', array('oLCompany' => $oLCompany));
    }
}

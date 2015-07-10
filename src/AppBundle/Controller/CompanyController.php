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

      $this->getDoctrine()
      ->getRepository('AppBundle:City')->findByRadius($oCity->getLatitude(), $oCity->getLongitude(), '5');


      $oLCompany = $this->getDoctrine()
        ->getRepository('AppBundle:Company')
        ->createQueryBuilder('c')
        ->join('c.city', 'ci')
        ->where('ci.name like :city_name')
        ->andWhere('ci.postalCode like :city_postal_code')
        ->setParameter('city_name', $city)
        ->setParameter('city_postal_code', $postal_code)
        ->getQuery()
        ->getResult();



      return $this->render('AppBundle:Company:list.'.$format.'.twig', array('oLCompany' => $oLCompany));
    }
}

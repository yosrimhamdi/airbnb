<?php

namespace App\Controller;

use App\Repository\AdRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Ad;

class AdController extends AbstractController {
  /**
   * @Route("/ads", name="ads_index")
   */
  public function index(AdRepository $repo): Response {
    $ads = $repo->findAll();

    return $this->render('ad/index.html.twig', [
      'ads' => $ads,
    ]);
  }

  /**
   * Showing single ad based on its slug
   *
   * @Route("/ads/{slug}", name="ads_show")
   *
   * @param Ad $ad
   * @return Response
   */
  public function show(Ad $ad) {
    return $this->render('ad/show.html.twig', [
      'ad' => $ad,
    ]);
  }
}

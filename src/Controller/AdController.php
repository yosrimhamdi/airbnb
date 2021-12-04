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
   * @Route("/ads/{id}", name="ads_show")
   */
  public function show(Ad $ad) {
    return new Response($ad->getTitle());
  }
}

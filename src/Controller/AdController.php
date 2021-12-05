<?php

namespace App\Controller;

use App\Repository\AdRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Ad;
use App\Form\AdType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

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
   * @Route("/ads/new", name="ads_create")
   */
  public function form(Request $request, EntityManagerInterface $manager) {
    $ad = new Ad();

    $form = $this->createForm(AdType::class, $ad);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      dd($ad);
    }

    return $this->render('ad/create.html.twig', [
      'adForm' => $form->createView(),
    ]);
  }

  /**
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

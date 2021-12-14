<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
  public function create(Request $request, EntityManagerInterface $manager) {
    $ad = new Ad();

    $form = $this->createForm(AdType::class, $ad);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      foreach ($ad->getImages() as $image) {
        $image->setAd($ad);
        $manager->persist($image);
      }

      $manager->persist($ad);
      $manager->flush();

      $this->addFlash('success', "ad {$ad->getTitle()} created successfully!");

      return $this->redirectToRoute('ads_show', [
        'slug' => $ad->getSlug(),
      ]);
    }

    return $this->render('ad/create.html.twig', [
      'form' => $form->createView(),
    ]);
  }

  /**
   * @Route("/ads/{slug}/edit", name="ads_edit")
   */
  public function edit(
    Ad $ad,
    Request $request,
    EntityManagerInterface $manager
  ) {
    $form = $this->createForm(AdType::class, $ad);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $manager->persist($ad);
      $manager->flush();

      $this->addFlash('success', "ad {$ad->getTitle()} updated successfully!");

      return $this->redirectToRoute('ads_show', [
        'slug' => $ad->getSlug(),
      ]);
    }

    return $this->render('ad/edit.html.twig', [
      'form' => $form->createView(),
      'ad' => $ad,
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

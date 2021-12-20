<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdController extends AbstractController {
  /**
   * @Route("/ads", name="ads_index")
   */
  public function index(AdRepository $repo): Response {
    $ads = $repo->findAll();

    return $this->render("ad/index.html.twig", [
      "ads" => $ads,
    ]);
  }

  /**
   * @Route("/ads/new", name="ads_create")
   * @IsGranted("ROLE_USER")
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

      $ad->setUser($this->getUser());

      $manager->persist($ad);
      $manager->flush();

      $this->addFlash("success", "ad {$ad->getTitle()} created successfully!");

      return $this->redirectToRoute("ads_show", [
        "slug" => $ad->getSlug(),
      ]);
    }

    return $this->render("ad/create.html.twig", [
      "form" => $form->createView(),
    ]);
  }

  /**
   * @Route("/ads/{slug}/edit", name="ads_edit")
   * @Security(
   *  "is_granted('ROLE_USER') and user === ad.getUser()",
   *  message="Your not the owner of this ad"
   * )
   */
  public function edit(
    Ad $ad,
    Request $request,
    EntityManagerInterface $manager
  ) {
    $ad->setContent(preg_replace("/(<p>|<\/p>)/", "", $ad->getContent()));

    $form = $this->createForm(AdType::class, $ad);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      foreach ($ad->getImages() as $image) {
        $image->setAd($ad);
        $manager->persist($image);
      }

      $manager->persist($ad);
      $manager->flush();

      $this->addFlash("success", "ad {$ad->getTitle()} updated successfully!");

      return $this->redirectToRoute("ads_show", [
        "slug" => $ad->getSlug(),
      ]);
    }

    return $this->render("ad/edit.html.twig", [
      "form" => $form->createView(),
      "ad" => $ad,
    ]);
  }

  /**
   * @Route("/ads/{slug}", name="ads_show")
   *
   * @param Ad $ad
   * @return Response
   */
  public function show(Ad $ad) {
    return $this->render("ad/show.html.twig", [
      "ad" => $ad,
    ]);
  }

  /**
   * Deleting a specific ad
   *
   * @Route("ads/{slug}/delete", name="ads_delete")
   * @Security(
   *  "is_granted('ROLE_USER') and user === ad.getUser()",
   *  message="you don't own this ad"
   * )
   *
   * @param Ad $ad
   * @param EntityManagerInterface $manager
   * @return Response
   */
  public function deleteAd(Ad $ad, EntityManagerInterface $manager) {
    $manager->remove($ad);
    $manager->flush();

    $this->addFlash("info", "Ad {$ad->getTitle()} has been deleted.");

    return $this->redirectToRoute("my_account");
  }
}

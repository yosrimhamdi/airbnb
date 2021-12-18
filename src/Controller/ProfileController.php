<?php

namespace App\Controller;

use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfileController extends AbstractController {
  /**
   * @Route("/profile", name="profile_update")
   */
  public function profile(
    Request $request,
    EntityManagerInterface $manager
  ): Response {
    $user = $this->getUser();

    $form = $this->createForm(ProfileType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $manager->persist($user);
      $manager->flush();

      $this->addFlash("success", "profile updated.");
    }

    return $this->render("profile/index.html.twig", [
      "form" => $form->createView(),
    ]);
  }
}

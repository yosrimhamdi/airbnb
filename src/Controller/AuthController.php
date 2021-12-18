<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController {
  /**
   * @Route("/login", name="auth_login")
   */
  public function login(AuthenticationUtils $util): Response {
    $error = $util->getLastAuthenticationError();
    $username = $util->getLastUsername();

    return $this->render("auth/login.html.twig", [
      "error" => $error,
      "username" => $username,
    ]);
  }

  /**
   *
   * @Route("/logout", name="auth_logout")
   */
  public function logout() {
  }

  /**
   *
   * @Route("/register", name="auth_register")
   */
  public function register(Request $request) {
    $user = new User();

    $form = $this->createForm(RegistrationType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      //
    }

    return $this->render("auth/register.html.twig", [
      "form" => $form->createView(),
    ]);
  }
}

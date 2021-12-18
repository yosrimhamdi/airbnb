<?php

namespace App\Controller;

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
}

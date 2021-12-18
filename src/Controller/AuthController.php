<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuthController extends AbstractController {
  /**
   * @Route("/login", name="auth_login")
   */
  public function login(): Response {
    return $this->render("auth/login.html.twig");
  }

  /**
   *
   * @Route("/logout", name="auth_logout")
   */
  public function logout() {
  }
}

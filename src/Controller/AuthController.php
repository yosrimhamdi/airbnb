<?php

namespace App\Controller;

use App\Entity\PasswordUpdate;
use App\Entity\User;
use App\Form\PasswordUpdateType;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
    // forward flashes!!!
  }

  /**
   *
   * @Route("/register", name="auth_register")
   */
  public function register(
    Request $request,
    EntityManagerInterface $manager,
    UserPasswordEncoderInterface $enocder
  ) {
    $user = new User();

    $form = $this->createForm(RegistrationType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $user->setPassword($enocder->encodePassword($user, $user->getPassword()));

      $manager->persist($user);
      $manager->flush();

      return $this->redirectToRoute("auth_login");
    }

    return $this->render("auth/register.html.twig", [
      "form" => $form->createView(),
    ]);
  }

  /**
   * @Route("/password/update", name="auth_password_update")
   */
  public function passwordUpdate(
    Request $request,
    EntityManagerInterface $manager,
    UserPasswordEncoderInterface $encoder
  ) {
    $user = $this->getUser();
    $passwordUpdate = new PasswordUpdate();

    $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $isValid = $encoder->isPasswordValid(
        $user,
        $passwordUpdate->getOldPassword()
      );

      if ($isValid) {
        $user->setPassword(
          $encoder->encodePassword($user, $passwordUpdate->getNewPassword())
        );

        $manager->flush();

        $this->addFlash("success", "password updated. login again.");

        return $this->redirectToRoute("auth_logout");
      } else {
        $this->addFlash("error", "wrong password");
      }
    }

    return $this->render("auth/password-update.html.twig", [
      "form" => $form->createView(),
    ]);
  }
}

<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController {
  /**
   * @Route("/login", name="auth_login")
   * @Security("is_anonymous()", message="Your are already logged in")
   */
  public function login(AuthenticationUtils $util): Response {
    $error = $util->getLastAuthenticationError();
    $username = $util->getLastUsername();

    return $this->render("account/login.html.twig", [
      "error" => $error,
      "username" => $username,
    ]);
  }

  /**
   *
   * @Route("/logout", name="logout")
   * @IsGranted("ROLE_USER")
   */
  public function logout() {
  }

  /**
   * @Route("/register", name="auth_register")
   * @Security("is_anonymous()", message="Your are already logged in")
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

    return $this->render("account/register.html.twig", [
      "form" => $form->createView(),
    ]);
  }

  /**
   * Showing the current logged in user profile
   *
   * @Route("/account/profile", name="my_account")
   * @IsGranted("ROLE_USER")
   *
   * @return Response
   */
  public function myAcccount() {
    return $this->render("user/show.html.twig", [
      "user" => $this->getUser(),
    ]);
  }

  /**
   * @Route("/account/profile/update", name="profile_update")
   * @IsGranted("ROLE_USER")
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

      return $this->redirectToRoute("account_profile");
    }

    return $this->render("profile/index.html.twig", [
      "form" => $form->createView(),
    ]);
  }

  /**
   * @Route("account/password/update", name="password_update")
   * @IsGranted("ROLE_USER")
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

        return $this->redirectToRoute("auth_logout");
      } else {
        $form->get("oldPassword")->addError(new FormError("wrong password."));
      }
    }

    return $this->render("account/password-update.html.twig", [
      "form" => $form->createView(),
    ]);
  }
}

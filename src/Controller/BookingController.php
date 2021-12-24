<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Form\BookingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookingController extends AbstractController {
  /**
   * @IsGranted("ROLE_USER")
   * @Route("/ads/{slug}/booking", name="booking_create")
   */
  public function book(
    Ad $ad,
    Request $request,
    EntityManagerInterface $manager
  ): Response {
    $booking = new Booking();

    $form = $this->createForm(BookingType::class, $booking);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $booking->setBooker($this->getUser())->setAd($ad);

      $manager->persist($booking);
      $manager->flush();
    }

    return $this->render("booking/book.html.twig", [
      "form" => $form->createView(),
      "ad" => $ad,
    ]);
  }
}

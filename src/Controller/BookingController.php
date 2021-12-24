<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Form\BookingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

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
      $nbrNights = $booking->getStartDate()->diff($booking->getEndDate())->days;

      $booking
        ->setBooker($this->getUser())
        ->setAd($ad)
        ->setCreatedAt(new \DateTime())
        ->setAmout($nbrNights * $ad->getAmount());

      $manager->persist($booking);
      $manager->flush();
    }

    return $this->render("booking/book.html.twig", [
      "form" => $form->createView(),
      "ad" => $ad,
    ]);
  }
}

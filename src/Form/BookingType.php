<?php

namespace App\Form;

use App\Entity\Booking;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BookingType extends ApplicationType {
  public function buildForm(
    FormBuilderInterface $builder,
    array $options
  ): void {
    $builder
      ->add(
        "startDate",
        DateType::class,
        $this->getConfig("Start date", "your first night")
      )
      ->add(
        "endDate",
        DateType::class,
        $this->getConfig("End date", "your last night")
      )
      ->add(
        "comment",
        TextareaType::class,
        $this->getConfig("Comment", "Write  a comment", [
          "required" => false,
        ])
      );
  }

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver->setDefaults([
      "data_class" => Booking::class,
    ]);
  }
}

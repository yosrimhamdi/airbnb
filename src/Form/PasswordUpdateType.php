<?php

namespace App\Form;

use App\Entity\PasswordUpdate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PasswordUpdateType extends AbstractType {
  use InputConfigTrait;

  public function buildForm(
    FormBuilderInterface $builder,
    array $options
  ): void {
    $builder
      ->add(
        "oldPassword",
        PasswordType::class,
        $this->getConfig("Old Password", "password")
      )
      ->add("newPassword", RepeatedType::class, [
        "type" => PasswordType::class,
        "invalid_message" => "The password fields must match.",
        "options" => ["attr" => ["class" => "password-field"]],
        "required" => true,
        "first_options" => $this->getConfig("New Password", "new password"),
        "second_options" => $this->getConfig(
          "Password Confirmation",
          "confirm password"
        ),
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver->setDefaults([
      "data_class" => PasswordUpdate::class,
    ]);
  }
}

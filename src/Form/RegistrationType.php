<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationType extends AbstractType {
  use InputConfigTrait;

  public function buildForm(
    FormBuilderInterface $builder,
    array $options
  ): void {
    $builder
      ->add(
        "firstName",
        TextType::class,
        $this->getConfig("FirstName", "FirstName")
      )
      ->add(
        "lastName",
        TextType::class,
        $this->getConfig("LastName", "lastname")
      )
      ->add("email", EmailType::class, $this->getConfig("Email", "email"))
      ->add(
        "password",
        RepeatedType::class,
        [
          "type" => PasswordType::class,
          "invalid_message" => "The password fields must match.",
          "options" => ["attr" => ["class" => "password-field"]],
          "required" => true,
          "first_options" => ["label" => "Password"],
          "second_options" => ["label" => "Repeat Password"],
        ],
        $this->getConfig("Password", "Choose a solid password")
      )
      ->add("photo", UrlType::class, $this->getConfig("Photo", "Url of you"))
      ->add(
        "introduction",
        TextType::class,
        $this->getConfig("Intro", "A brief into")
      )
      ->add(
        "description",
        TextareaType::class,
        $this->getConfig("Description", "A long description")
      );
  }

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver->setDefaults([
      "data_class" => User::class,
    ]);
  }
}

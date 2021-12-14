<?php

namespace App\Form;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageType extends AbstractType {
  use InputConfigTrait;

  public function buildForm(
    FormBuilderInterface $builder,
    array $options
  ): void {
    $builder
      ->add('url', TextType::class, $this->getConfig('Image Url', 'Url'))
      ->add(
        'caption',
        TextType::class,
        $this->getConfig('Caption', 'Image description')
      );
  }

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver->setDefaults([
      'data_class' => Image::class,
    ]);
  }
}

<?php

namespace App\Form;

use App\Entity\Ad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdType extends AbstractType {
  /**
   * Return input config(label + placeholder)
   *
   * @param string $label
   * @param string $placeholder
   * @return array
   */
  public function getConfig($label, $placeholder) {
    return [
      'label' => $label,
      'attr' => ['placeholder' => $placeholder],
    ];
  }

  public function buildForm(
    FormBuilderInterface $builder,
    array $options
  ): void {
    $builder
      ->add(
        'title',
        TextType::class,
        $this->getConfig('Titre', "Titre de l'annonce")
      )
      ->add(
        'coverImage',
        UrlType::class,
        $this->getConfig('Image de l\'annonce', "URL de l'image")
      )
      ->add(
        'introduction',
        TextType::class,
        $this->getConfig('Intro', "Into de l'annonce")
      )
      ->add(
        'content',
        TextareaType::class,
        $this->getConfig('Description', "description de l'annonce")
      )
      ->add(
        'rooms',
        IntegerType::class,
        $this->getConfig('Nombre de chambres', 'Nombre de chambres')
      )
      ->add(
        'price',
        MoneyType::class,
        $this->getConfig('Prix par nuit', 'Prix')
      )
      ->add('images', CollectionType::class, [
        'entry_type' => ImageType::class,
        'allow_add' => true,
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver->setDefaults([
      'data_class' => Ad::class,
    ]);
  }
}

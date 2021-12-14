<?php

namespace App\Form;

trait InputConfigTrait {
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
}

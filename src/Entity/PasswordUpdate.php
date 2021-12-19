<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;

class PasswordUpdate {
  private $oldPassword;

  /**
   * @Assert\Length(min="4")
   */
  private $newPassword;

  public function getId(): ?int {
    return $this->id;
  }

  public function getOldPassword(): ?string {
    return $this->oldPassword;
  }

  public function setOldPassword(string $oldPassword): self {
    $this->oldPassword = $oldPassword;

    return $this;
  }

  public function getNewPassword(): ?string {
    return $this->newPassword;
  }

  public function setNewPassword(string $newPassword): self {
    $this->newPassword = $newPassword;

    return $this;
  }
}

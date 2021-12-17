<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Repository\UserRepository;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class User implements UserInterface {
  /**
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private $firstName;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private $lastName;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private $email;

  /**
   * @ORM\Column(type="text")
   */
  private $password;

  /**
   * @ORM\Column(type="string", length=255, nullable=true)
   */
  private $photo;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private $introduction;

  /**
   * @ORM\Column(type="text")
   */
  private $description;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private $slug;

  /**
   * @ORM\OneToMany(targetEntity=Ad::class, mappedBy="user")
   */
  private $ads;

  public function __construct() {
    $this->ads = new ArrayCollection();
  }

  public function getId(): ?int {
    return $this->id;
  }

  public function getFirstName(): ?string {
    return $this->firstName;
  }

  public function setFirstName(string $firstName): self {
    $this->firstName = $firstName;

    return $this;
  }

  public function getLastName(): ?string {
    return $this->lastName;
  }

  public function setLastName(string $lastName): self {
    $this->lastName = $lastName;

    return $this;
  }

  public function getEmail(): ?string {
    return $this->email;
  }

  public function setEmail(string $email): self {
    $this->email = $email;

    return $this;
  }

  public function getPassword(): ?string {
    return $this->password;
  }

  public function setPassword(string $password): self {
    $this->password = $password;

    return $this;
  }

  public function getPhoto(): ?string {
    return $this->photo;
  }

  public function setPhoto(?string $photo): self {
    $this->photo = $photo;

    return $this;
  }

  public function getIntroduction(): ?string {
    return $this->introduction;
  }

  public function setIntroduction(string $introduction): self {
    $this->introduction = $introduction;

    return $this;
  }

  public function getDescription(): ?string {
    return $this->description;
  }

  public function setDescription(string $description): self {
    $this->description = $description;

    return $this;
  }

  public function getSlug(): ?string {
    return $this->slug;
  }

  public function setSlug(string $slug): self {
    $this->slug = $slug;

    return $this;
  }

  /**
   * @return Collection|Ad[]
   */
  public function getAds(): Collection {
    return $this->ads;
  }

  public function addAd(Ad $ad): self {
    if (!$this->ads->contains($ad)) {
      $this->ads[] = $ad;
      $ad->setUser($this);
    }

    return $this;
  }

  public function removeAd(Ad $ad): self {
    if ($this->ads->removeElement($ad)) {
      // set the owning side to null (unless already changed)
      if ($ad->getUser() === $this) {
        $ad->setUser(null);
      }
    }

    return $this;
  }

  /**
   * @ORM\PrePersist
   * @ORM\PreUpdate
   */
  public function slugInit() {
    $slugify = new Slugify();
    $this->slug = $slugify->slugify($this->firstName . " " . $this->lastName);
  }

  public function getRoles() {
    return ["USER_ROLE"];
  }

  public function getUsername() {
    return $this->email;
  }

  public function eraseCredentials() {
  }

  public function getSalt() {
  }
}

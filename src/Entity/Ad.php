<?php

namespace App\Entity;

use App\Entity\BetweenBoundariesDays;
use Cocur\Slugify\Slugify;
use App\Repository\AdRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=AdRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(
 *  fields={"title"},
 *  message="there is an ad with the same title"
 * )
 */
class Ad {
    use BetweenBoundariesDays;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="text")
     */
    private $introduction;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $coverImage;

    /**
     * @ORM\Column(type="integer")
     */
    private $rooms;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="ad", orphanRemoval=true)
     * @Assert\Valid()
     */
    private $images;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="ads")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Booking::class, mappedBy="ad")
     */
    private $bookings;

    public function __construct() {
        $this->images = new ArrayCollection();
        $this->bookings = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function slugInit() {
        $slugify = new Slugify();
        $this->slug = $slugify->slugify($this->title);
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getTitle(): ?string {
        return $this->title;
    }

    public function setTitle(string $title): self {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string {
        return $this->slug;
    }

    public function setSlug(string $slug): self {
        $this->slug = $slug;

        return $this;
    }

    public function getPrice(): ?float {
        return $this->price;
    }

    public function setPrice(float $price): self {
        $this->price = $price;

        return $this;
    }

    public function getIntroduction(): ?string {
        return $this->introduction;
    }

    public function setIntroduction(string $introduction): self {
        $this->introduction = $introduction;

        return $this;
    }

    public function getContent(): ?string {
        return $this->content;
    }

    public function setContent(string $content): self {
        $this->content = $content;

        return $this;
    }

    public function getcoverImage(): ?string {
        return $this->coverImage;
    }

    public function setcoverImage(string $coverImage): self {
        $this->coverImage = $coverImage;

        return $this;
    }

    public function getRooms(): ?int {
        return $this->rooms;
    }

    public function setRooms(int $rooms): self {
        $this->rooms = $rooms;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection {
        return $this->images;
    }

    public function addImage(Image $image): self {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setAd($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getAd() === $this) {
                $image->setAd(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User {
        return $this->user;
    }

    public function setUser(?User $user): self {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Booking[]
     */
    public function getBookings(): Collection {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): self {
        if (!$this->bookings->contains($booking)) {
            $this->bookings[] = $booking;
            $booking->setAd($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getAd() === $this) {
                $booking->setAd(null);
            }
        }

        return $this;
    }

    public function getNotAvailableDays() {
        $notAvailableDays = [];

        foreach ($this->bookings as $booking) {
            $days = $this->getBetweenBoundariesDays(
                $booking->getStartDate(),
                $booking->getEndDate()
            );

            $notAvailableDays = array_merge($days, $notAvailableDays);
        }

        return $notAvailableDays;
    }
}

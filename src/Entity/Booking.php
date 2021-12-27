<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\BetweenBoundariesDays;
use App\Repository\BookingRepository;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BookingRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Booking {
    use BetweenBoundariesDays;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date()
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date()
     */
    private $endDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\ManyToOne(targetEntity=Ad::class, inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ad;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $booker;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    public function getId(): ?int {
        return $this->id;
    }

    public function getStartDate(): ?\DateTimeInterface {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self {
        $this->endDate = $endDate;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAmount(): ?float {
        return $this->amount;
    }

    public function setAmount(float $amount): self {
        $this->amount = $amount;

        return $this;
    }

    public function getAd(): ?Ad {
        return $this->ad;
    }

    public function setAd(?Ad $ad): self {
        $this->ad = $ad;

        return $this;
    }

    public function getBooker(): ?User {
        return $this->booker;
    }

    public function setBooker(?User $booker): self {
        $this->booker = $booker;

        return $this;
    }

    public function getComment(): ?string {
        return $this->comment;
    }

    public function setComment(?string $comment): self {
        $this->comment = $comment;

        return $this;
    }

    public function getNumberOfNights() {
        return $this->getStartDate()->diff($this->getEndDate())->days;
    }

    /**
     * Undocumented function
     *
     * @ORM\PrePersist
     *
     * @return void
     */
    public function prePersist() {
        $numberOfNights = $this->getNumberOfNights();

        $this->createdAt = new \DateTime();
        $this->amount = $this->ad->getPrice() * $numberOfNights;
    }

    public function isBookable() {
        $notAvailableDays = $this->ad->getNotAvailableDays();

        $days = $this->getBetweenBoundariesDays(
            $this->startDate,
            $this->endDate
        );

        foreach ($days as $day) {
            if (in_array($day, $notAvailableDays)) {
                return false;
            }
        }

        return true;
    }
}

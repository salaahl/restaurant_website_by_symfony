<?php

namespace App\Entity;

use App\Repository\ReservationDateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReservationDateRepository::class)]
#[ORM\Table(name: 'reservation_date')]
#[ORM\UniqueConstraint(columns: ['date'])]
class ReservationDate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime', unique: true)]
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual("today", message: "La date doit être égale ou postérieure à aujourd'hui.")]
    private ?\DateTimeInterface $date = null;

    #[ORM\OneToMany(mappedBy: 'date', targetEntity: Reservation::class, cascade: ['remove'])]
    private Collection $reservations;

    #[ORM\OneToMany(mappedBy: 'date', targetEntity: Seat::class, cascade: ['remove'])]
    private Collection $seats;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->seats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setDate($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            if ($reservation->getDate() === $this) {
                $reservation->setDate(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Seat>
     */
    public function getSeats(): Collection
    {
        return $this->seats;
    }

    public function addSeat(Seat $seat): self
    {
        if (!$this->seats->contains($seat)) {
            $this->seats->add($seat);
            $seat->setDate($this);
        }

        return $this;
    }

    public function removeSeat(Seat $seat): self
    {
        if ($this->seats->removeElement($seat)) {
            if ($seat->getDate() === $this) {
                $seat->setDate(null);
            }
        }

        return $this;
    }
}

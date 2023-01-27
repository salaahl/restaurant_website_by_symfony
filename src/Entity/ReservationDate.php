<?php

namespace App\Entity;

use App\Repository\ReservationDateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationDateRepository::class)]
class ReservationDate
{
    #[ORM\Id]
    #[ORM\Column(length: 255)]
    private ?string $reservation_date = null;

    #[ORM\OneToMany(mappedBy: 'reservation_date', targetEntity: Reservation::class)]
    private Collection $reservations;

    #[ORM\OneToMany(mappedBy: 'date', targetEntity: Seats::class)]
    private Collection $seats;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->seats = new ArrayCollection();
    }

    public function getReservationDate(): ?string
    {
        return $this->reservation_date;
    }

    public function setReservationDate(string $reservation_date): self
    {
        $this->reservation_date = $reservation_date;

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
            $reservation->setReservationDate($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getReservationDate() === $this) {
                $reservation->setReservationDate(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Seats>
     */
    public function getSeats(): Collection
    {
        return $this->seats;
    }

    public function addSeat(Seats $seat): self
    {
        if (!$this->seats->contains($seat)) {
            $this->seats->add($seat);
            $seat->setDate($this);
        }

        return $this;
    }

    public function removeSeat(Seats $seat): self
    {
        if ($this->seats->removeElement($seat)) {
            // set the owning side to null (unless already changed)
            if ($seat->getDate() === $this) {
                $seat->setDate(null);
            }
        }

        return $this;
    }
}

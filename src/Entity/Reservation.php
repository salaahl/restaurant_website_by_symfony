<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\Column(length: 255)]
    private ?string $mail = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable:false, name:"ReservationDate", referencedColumnName:"reservation_date")]
    private ?ReservationDate $reservation_date = null;

    #[ORM\OneToMany(mappedBy: 'mail', targetEntity: Seats::class)]
    private Collection $seats;

    #[ORM\Column(length: 255)]
    private ?string $surname = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone_number = null;

    #[ORM\Column]
    private ?int $seat_reserved = null;

    public function __construct()
    {
        $this->seats = new ArrayCollection();
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getReservationDate(): ?ReservationDate
    {
        return $this->reservation_date;
    }

    public function setReservationDate(?ReservationDate $reservation_date): self
    {
        $this->reservation_date = $reservation_date;

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
            $seat->setMail($this);
        }

        return $this;
    }

    public function removeSeat(Seats $seat): self
    {
        if ($this->seats->removeElement($seat)) {
            // set the owning side to null (unless already changed)
            if ($seat->getMail() === $this) {
                $seat->setMail(null);
            }
        }

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(?string $phone_number): self
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    public function getSeatReserved(): ?int
    {
        return $this->seat_reserved;
    }

    public function setSeatReserved(int $seat_reserved): self
    {
        $this->seat_reserved = $seat_reserved;

        return $this;
    }
}

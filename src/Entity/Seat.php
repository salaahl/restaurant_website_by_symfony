<?php

namespace App\Entity;

use App\Repository\SeatRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeatRepository::class)]
class Seat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $hour = null;

    #[ORM\Column]
    private ?int $seat = null;

    #[ORM\ManyToOne(inversedBy: 'seats')]
    #[ORM\JoinColumn(nullable: false, name: "reservation_date_id", referencedColumnName: "id")]
    private ?ReservationDate $reservationDate = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHour(): ?\DateTimeInterface
    {
        return $this->hour;
    }

    public function setHour(\DateTimeInterface $hour): self
    {
        $this->hour = $hour;

        return $this;
    }

    public function getSeat(): ?int
    {
        return $this->seat;
    }

    public function setSeat(int $seat): self
    {
        $this->seat = $seat;

        return $this;
    }

    public function getReservationDate(): ?ReservationDate
    {
        return $this->reservationDate;
    }

    public function setReservationDate(?ReservationDate $reservationDate): self
    {
        $this->reservationDate = $reservationDate;

        return $this;
    }
}

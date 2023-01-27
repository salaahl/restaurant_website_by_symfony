<?php

namespace App\Entity;

use App\Repository\SeatsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeatsRepository::class)]
class Seats
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
    #[ORM\JoinColumn(nullable:false, name:"ReservationDate", referencedColumnName:"reservation_date")]
    private ?ReservationDate $date = null;

    #[ORM\ManyToOne(inversedBy: 'seats')]
    #[ORM\JoinColumn(nullable:true, name:"Reservation", referencedColumnName:"mail")]
    private ?Reservation $mail = null;

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

    public function getDate(): ?ReservationDate
    {
        return $this->date;
    }

    public function setDate(?ReservationDate $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getMail(): ?Reservation
    {
        return $this->mail;
    }

    public function setMail(?Reservation $mail): self
    {
        $this->mail = $mail;

        return $this;
    }
}

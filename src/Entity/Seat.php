<?php

namespace App\Entity;

use App\Repository\SeatRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SeatRepository::class)]
class Seat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: 'float', nullable: false)]
    private ?float $hour = null;

    #[Assert\NotBlank]
    #[Assert\Range(min: 1, max: 10, notInRangeMessage: "Le numéro de siège doit être compris entre {{ min }} et {{ max }}.")]
    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $seats_available = null;

    #[ORM\ManyToOne(inversedBy: 'seats')]
    #[ORM\JoinColumn(nullable: false, name: "reservation_date_id", referencedColumnName: "id")]
    private ?ReservationDate $date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHour(): ?float
    {
        return $this->hour;
    }

    public function setHour(float $hour): self
    {
        $this->hour = $hour;

        return $this;
    }

    public function getSeatsAvailable(): ?int
    {
        return $this->seats_available;
    }

    public function setSeatsAvailable(int $seats_available): self
    {
        $this->seats_available = $seats_available;

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
}

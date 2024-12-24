<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\Email]
    #[Assert\NotBlank]
    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private ?string $email = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ReservationDate $date = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private ?string $surname = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private ?string $name = null;

    #[Assert\Regex(
        pattern: '/^\+?[0-9\s\-]{7,15}$/',
        message: 'Le numéro de téléphone doit être valide.'
    )]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $phone_number = null;

    #[Assert\NotBlank]
    #[Assert\Positive(message: 'Le nombre de places doit être supérieur à zéro.')]
    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $seats = null;

    #[ORM\Column(type: 'float', nullable: false)]
    private ?int $hour = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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

    public function getSeats(): ?int
    {
        return $this->seats;
    }

    public function setSeats(int $seats): self
    {
        $this->seats = $seats;

        return $this;
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
}

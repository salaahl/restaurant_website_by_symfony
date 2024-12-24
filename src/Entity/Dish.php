<?php

namespace App\Entity;

use App\Repository\DishRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DishRepository::class)]
class Dish
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    #[ORM\Column(length: 255, nullable: false, type: 'string')]
    private ?string $name = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 1000)]
    #[ORM\Column(nullable: false, type: 'text')]
    private ?string $description = null;

    #[Assert\NotNull]
    #[Assert\PositiveOrZero]
    #[ORM\Column(nullable: false, type: 'float')]
    private ?float $price = null;

    #[ORM\ManyToOne(inversedBy: 'dishes', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false, name: "menu_id", referencedColumnName: "id", onDelete: "CASCADE")]
    private ?Menu $menu = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getMenu(): ?Menu
    {
        return $this->menu;
    }

    public function setMenu(?Menu $menu): self
    {
        $this->menu = $menu;

        return $this;
    }
}

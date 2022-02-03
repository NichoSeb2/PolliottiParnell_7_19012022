<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[UniqueEntity(fields: "name")]
class Product {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["product"])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["product"])]
    private $name;

    #[ORM\Column(type: 'float')]
    #[Groups(["product"])]
    private $price;

    #[ORM\Column(type: 'text')]
    #[Groups(["product"])]
    private $description;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["product"])]
    private $color;

    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float {
        return $this->price;
    }

    public function setPrice(float $price): self {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescription(string $description): self {
        $this->description = $description;

        return $this;
    }

    public function getColor(): ?string {
        return $this->color;
    }

    public function setColor(string $color): self {
        $this->color = $color;

        return $this;
    }
}

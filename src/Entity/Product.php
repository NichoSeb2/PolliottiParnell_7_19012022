<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use JMS\Serializer\Annotation\Groups;
use Hateoas\Configuration\Annotation as Hateoas;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[UniqueEntity(fields: "name")]
/**
 * @Hateoas\Relation(
 *     "self",
 *     href = "expr('/api/products/' ~ object.getId())",
 *     attributes = {
 *         "method": "GET"
 *     },
 *     exclusion = @Hateoas\Exclusion(
 *         groups = {"product"}
 *     )
 * )
 */
class Product {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["full", "product"])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["full", "product"])]
    private $name;

    #[ORM\Column(type: 'float')]
    #[Groups(["full", "product"])]
    private $price;

    #[ORM\Column(type: 'text')]
    #[Groups(["full", "product"])]
    private $description;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["full", "product"])]
    private $color;

    /**
     * Hook timestampable behavior
     * updates createdAt, updatedAt fields
     */
    use TimestampableEntity;

    public function __construct() {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

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

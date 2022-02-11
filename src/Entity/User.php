<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use DateTime;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: "email")]
class User {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["full", "user"])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["full", "user"])]
    #[Assert\NotBlank]
    #[Assert\Length(
        max: 255,
    )]
    private $firstName;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["full", "user"])]
    #[Assert\NotBlank]
    #[Assert\Length(
        max: 255,
    )]
    private $lastName;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["full", "user"])]
    #[Assert\NotBlank]
    #[Assert\Email()]
    #[Assert\Length(
        max: 255,
    )]
    private $email;

    #[ORM\ManyToOne(targetEntity: Society::class, inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["full", "user"])]
    #[Assert\NotBlank]
    private $society;

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

    public function getFirstName(): ?string {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string {
        return $this->email;
    }

    public function setEmail(string $email): self {
        $this->email = $email;

        return $this;
    }

    public function getSociety(): ?Society {
        return $this->society;
    }

    public function setSociety(?Society $society): self {
        $this->society = $society;

        return $this;
    }
}

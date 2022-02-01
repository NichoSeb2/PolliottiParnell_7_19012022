<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["user", "user_detail"])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["user", "user_detail"])]
    private $firstName;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["user", "user_detail"])]
    private $lastName;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["user", "user_detail"])]
    private $email;

    #[ORM\ManyToOne(targetEntity: Society::class, inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["user_detail"])]
    private $society;

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

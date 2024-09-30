<?php

namespace App\Entity;

use App\Repository\GuestRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Attributes as OA;

#[ORM\Entity(repositoryClass: GuestRepository::class)]
class Guest
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\SequenceGenerator(sequenceName: "guest_id_seq", allocationSize: 1)]
    #[ORM\Column]
    #[OA\Property(description: "ID", example: 1)]
    #[Groups(["guest:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[OA\Property(description: "Имя", example: "Ivan")]
    #[Groups(["guest:read", "guest:write"])]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[OA\Property(description: "Фамилия", example: "Ivanov")]
    #[Groups(["guest:read", "guest:write"])]
    private ?string $lastName = null;

    #[ORM\Column(length: 20, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^\+\d{10,15}$/')]
    #[OA\Property(description: "Телефон", example: "+1234567890")]
    #[Groups(["guest:read", "guest:write"])]
    private ?string $phone = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    #[OA\Property(description: "Email", example: "example@example.com")]
    #[Groups(["guest:read", "guest:write"])]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[OA\Property(description: "Страна", example: "Russia")]
    #[Groups(["guest:read", "guest:write"])]
    private ?string $country = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): static
    {
        $this->country = $country;

        return $this;
    }
}

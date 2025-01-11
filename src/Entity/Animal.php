<?php

namespace App\Entity;

use App\Repository\AnimalRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AnimalRepository::class)]
class Animal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom de l'animal ne peut pas être vide.")]
    #[Assert\Length(min: 2, max: 14, minMessage: "Le nom de l'animal doit contenir au moins {{ limit }} caractères.", maxMessage: "Le nom de l'animal ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $Name = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "L'âge de l'animal ne peut pas être vide.")]
    #[Assert\Range(min: 0, max: 200, notInRangeMessage: "L'âge de l'animal doit être compris entre {{ min }} et {{ max }}.")]
    private ?int $Age = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): static
    {
        $this->Name = $Name;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->Age;
    }

    public function setAge(int $Age): static
    {
        $this->Age = $Age;

        return $this;
    }
}

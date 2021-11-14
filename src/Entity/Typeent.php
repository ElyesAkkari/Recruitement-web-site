<?php

namespace App\Entity;

use App\Repository\TypeentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=TypeentRepository::class)
 */
class Typeent
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Ce champs est obligatoire")
     * @Assert\Length(min="6", minMessage="taille min du Titre est 6 charactéres")
     */
    private $titre;

    /**
     * @Assert\NotBlank(message="Ce champs est obligatoire")
     * @ORM\Column(type="string", length=255,nullable=true)
     * @Assert\Length(min="6", minMessage="taille min du description est 6 charactéres")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Entretient::class, inversedBy="typeents")
     */
    private $entretient;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getEntretient(): ?Entretient
    {
        return $this->entretient;
    }

    public function setEntretient(?Entretient $entretient): self
    {
        $this->entretient = $entretient;

        return $this;
    }
}

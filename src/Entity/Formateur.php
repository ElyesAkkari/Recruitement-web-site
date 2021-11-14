<?php

namespace App\Entity;

use App\Repository\FormateurRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use symfony\Component\Serializer\annotaion\groups;




/**
 * @ORM\Entity(repositoryClass=FormateurRepository::class)
 */
class Formateur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=250)
     * @Assert\NotBlank(message="tapez votre nom")
     * @Assert\Type("string")
     * @Assert\Regex(
       pattern="/\d/",
       match=false,
       message="ton nom ne doit pas contenir un numero"
      )

     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=250)
     * @Assert\NotBlank(message="tapez votre prenom")
     * @Assert\Regex(
       pattern="/\d/",
       match=false,
      message="ton prenom ne doit pas contenir un numero"
      )



     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=250)
     * @Assert\NotBlank(message="tapez votre mail")
     * @Assert\Email(message = "ton email '{{ value }}' n'est pas un email valide.")
     * checkMX = true // c a d l'email est reel (il a ete creer)
     */
    private $email;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Positive(message="les numero doit etre positive")
     * @Assert\NotBlank(message="tapez votre numero")
     * @Assert\Type("integer")

     */
    private $numtel;

    /**
     * @ORM\Column(type="string", length=250)
     * @Assert\NotBlank(message="Ajouter une image jpg")
     * @Assert\File(mimeTypes={ "image/jpeg" })


     */
    private $image;

    /**
     * @ORM\OneToOne(targetEntity=Formation::class, mappedBy="nomformateur", cascade={"persist", "remove"})
     */
    private $formation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
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

    public function getNumtel(): ?int
    {
        return $this->numtel;
    }

    public function setNumtel(int $numtel): self
    {
        $this->numtel = $numtel;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage( $image)
    {
        $this->image = $image;

        return $this;
    }

    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    public function setFormation(Formation $formation): self
    {
        // set the owning side of the relation if necessary
        if ($formation->getNomformateur() !== $this) {
            $formation->setNomformateur($this);
        }

        $this->formation = $formation;

        return $this;
    }
}

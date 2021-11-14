<?php

namespace App\Entity;

use App\Repository\EntretientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Mgilet\NotificationBundle\Annotation\Notifiable;

use Mgilet\NotificationBundle\NotifiableInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Notifiable(name="entretient")
 * @ORM\Entity(repositoryClass=EntretientRepository::class)
 */
class Entretient implements NotifiableInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Ce champs est obligatoire")
     * @ORM\Column(type="date", nullable=true)
     */
    private $datedeb;


    /**
     * @Assert\NotBlank(message="Ce champs est obligatoire")
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(min="6", minMessage="taille min du champs Etat est 6 charactéres")
     */
    private $etat;


    /**
     * @Assert\NotBlank(message="Ce champs est obligatoire")
     * @Assert\Length(min="6", minMessage="taille min du champs Resultat est 6 charactéres")
     * @ORM\Column(type="string", length=255)
     */
    private $resultat;

    /**
     * @Assert\NotBlank(message="Ce champs est obligatoire")
     * @ORM\Column(type="string", length=14)
     */
    private $NumeroTelephone;

    /**
     * @ORM\Column(type="time")
     */
    private $temps;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $recruteur;

    /**
     * @Assert\NotBlank(message="Ce champs est obligatoire")
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="7", minMessage="taille min du champs Mail est 7 charactéres")
     */
    private $mail;

    /**
     * @ORM\OneToMany(targetEntity=Typeent::class, mappedBy="entretient",cascade={"all"},orphanRemoval=true)
     */
    private $typeents;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="entretients")
     */
    private $user;


    public function __construct()
    {
        $this->typeents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getDatedeb(): ?\DateTimeInterface
    {
        return $this->datedeb;
    }

    public function setDatedeb(?\DateTimeInterface $datedeb): self
    {
        $this->datedeb = $datedeb;

        return $this;
    }


    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }


    public function getResultat(): ?string
    {
        return $this->resultat;
    }

    public function setResultat(string $resultat): self
    {
        $this->resultat = $resultat;

        return $this;
    }

    public function getTemps(): ?\DateTimeInterface
    {
        return $this->temps;
    }

    public function setTemps(\DateTimeInterface $temps): self
    {
        $this->temps = $temps;

        return $this;
    }

    public function getRecruteur(): ?string
    {
        return $this->recruteur;
    }

    public function setRecruteur(string $recruteur): self
    {
        $this->recruteur = $recruteur;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * @return Collection|Typeent[]
     */
    public function getTypeents(): Collection
    {
        return $this->typeents;
    }

    public function addTypeent(Typeent $typeent): self
    {
        if (!$this->typeents->contains($typeent)) {
            $this->typeents[] = $typeent;
            $typeent->setEntretient($this);
        }

        return $this;
    }

    public function removeTypeent(Typeent $typeent): self
    {
        if ($this->typeents->removeElement($typeent)) {
            // set the owning side to null (unless already changed)
            if ($typeent->getEntretient() === $this) {
                $typeent->setEntretient(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumeroTelephone()
    {
        return $this->NumeroTelephone;
    }

    /**
     * @param mixed $NumeroTelephone
     */
    public function setNumeroTelephone($NumeroTelephone): void
    {
        $this->NumeroTelephone = $NumeroTelephone;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

}
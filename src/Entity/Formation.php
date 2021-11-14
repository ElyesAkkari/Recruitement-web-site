<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=FormationRepository::class)
 */
class Formation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="tapez le prix")
     * @Assert\Positive(message="les numero doit etre positive")


     */
    private $prix;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="tapez le nbr de participant")
     * @Assert\Positive(message="les numero doit etre positive")
     * @Assert\Type("integer")



     */
    private $nbrpartricipant;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="tapez le nbr d'heures")
     * @Assert\Positive(message="les numero doit etre positive")
     * @Assert\Type("integer")

     */
    private $nbrheures;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="tapez la date")

     */
    private $datedeb;



    /**
     * @ORM\Column(type="string", length=250)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=250)
     * @Assert\NotBlank(message="Ajouter une image jpg")
     * @Assert\File(mimeTypes={ "image/jpeg" })
     */
    private $image;

    /**
     * @ORM\OneToOne(targetEntity=Formateur::class, inversedBy="formation", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $nomformateur;

    /**
     * @ORM\OneToMany(targetEntity=Commantaire::class, mappedBy="idformation", cascade={"persist", "remove"})
     */
    private $commantaires;

    /**
     * @ORM\Column(type="text")
     */
    private $descrption;

    /**
     * @ORM\ManyToOne(targetEntity=Categories::class, inversedBy="idformation")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity=Participants::class, mappedBy="formation")
     */
    private $participants;











    public function __construct()
    {
        $this->idformateur = new ArrayCollection();
        $this->commantaires = new ArrayCollection();
        $this->participants = new ArrayCollection();


    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getNbrPartricipant(): ?int
    {
        return $this->nbrpartricipant;
    }

    public function setNbrPartricipant(int $nbrpartricipant): self
    {
        $this->nbrpartricipant = $nbrpartricipant;

        return $this;
    }

    public function getNbrHeures(): ?int
    {
        return $this->nbrheures;
    }

    public function setNbrHeures(int $nbrheures): self
    {
        $this->nbrheures = $nbrheures;

        return $this;
    }

    public function getDateDeb(): ?\DateTimeInterface
    {
        return $this->datedeb;
    }

    public function setDateDeb(\DateTimeInterface $datedeb): self
    {
        $this->datedeb = $datedeb;

        return $this;
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

    public function getImage()
    {
        return $this->image;
    }

    public function setImage( $image)
    {
        $this->image = $image;

        return $this;
    }

    public function getNomformateur(): ?Formateur
    {
        return $this->nomformateur;
    }

    public function setNomformateur(Formateur $nomformateur): self
    {
        $this->nomformateur = $nomformateur;

        return $this;
    }

    /**
     * @return Collection|Commantaire[]
     */
    public function getCommantaires(): Collection
    {
        return $this->commantaires;
    }

    public function addCommantaire(Commantaire $commantaire): self
    {
        if (!$this->commantaires->contains($commantaire)) {
            $this->commantaires[] = $commantaire;
            $commantaire->setIdformation($this);
        }

        return $this;
    }

    public function removeCommantaire(Commantaire $commantaire): self
    {
        if ($this->commantaires->removeElement($commantaire)) {
            // set the owning side to null (unless already changed)
            if ($commantaire->getIdformation() === $this) {
                $commantaire->setIdformation(null);
            }
        }

        return $this;
    }

    public function getDescrption(): ?string
    {
        return $this->descrption;
    }

    public function setDescrption(string $descrption): self
    {
        $this->descrption = $descrption;

        return $this;
    }

    public function getCategories(): ?Categories
    {
        return $this->categories;
    }

    public function setCategories(?Categories $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @return Collection|Participants[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participants $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
            $participant->setFormation($this);
        }

        return $this;
    }

    public function removeParticipant(Participants $participant): self
    {
        if ($this->participants->removeElement($participant)) {
            // set the owning side to null (unless already changed)
            if ($participant->getFormation() === $this) {
                $participant->setFormation(null);
            }
        }

        return $this;
    }







}

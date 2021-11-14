<?php

namespace App\Entity;

use App\Repository\OffreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Captcha\Bundle\CaptchaBundle\Validator\Constraints as CaptchaAssert;

/**
 *  @Vich\Uploadable
 * @ORM\Entity(repositoryClass=OffreRepository::class)
 */
class Offre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     *
     */
    private $type;

    /**
     *
     * @ORM\Column(type="string", length=20,nullable=false)
     * @Assert\NotBlank(message="le type est obligatoire")
     */

    private $noms;
/**
* @ORM\Column(type="string", length=255)
*
* @var string|null
     */
    private $imageName;
    /**
     * @Vich\UploadableField(mapping="property_image", fileNameProperty="imageName")
     * @var File|null
     */
    private $imageFile;
    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="description est obligatoire")
     */
    private $description;



    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="offre",cascade={"all"},orphanRemoval=true)
     */
    private $commentaires;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mailen;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $departement;

    /**
     * @CaptchaAssert\ValidCaptcha(
     *      message = "CAPTCHA validation failed, try again."
     * )
     */
    protected $captchaCode;

    /**
     * @ORM\Column(type="date")
     */
    private $datedeb;

    /**
     * @ORM\Column(type="date")
     */
    private $datefin;

    /**
     * @ORM\OneToMany(targetEntity=Participation::class, mappedBy="offre")
     */
    private $participations;



    public function getCaptchaCode()
    {
        return $this->captchaCode;
    }

    public function setCaptchaCode($captchaCode)
    {
        $this->captchaCode = $captchaCode;
    }

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
        $this->participations = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getNoms(): ?string
    {
        return $this->noms;
    }

    public function setNoms(string $noms): self
    {
        $this->noms = $noms;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

/**
* @return string|null
*/
    public function getImageName(): ?string
    {
        return $this->imageName;
    }

/**
* @param string|null $imageName
*/
    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

/**
* @return File|null
*/
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

/**
* @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
*/
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;
    }



    /**
     * @return Collection|Commentaire[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setOffre($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getOffre() === $this) {
                $commentaire->setOffre(null);
            }
        }

        return $this;
    }

    public function getMailen(): ?string
    {
        return $this->mailen;
    }

    public function setMailen(string $mailen): self
    {
        $this->mailen = $mailen;

        return $this;
    }

    public function getDepartement(): ?string
    {
        return $this->departement;
    }

    public function setDepartement(string $departement): self
    {
        $this->departement = $departement;

        return $this;
    }

    public function getDatedeb(): ?\DateTimeInterface
    {
        return $this->datedeb;
    }

    public function setDatedeb(\DateTimeInterface $datedeb): self
    {
        $this->datedeb = $datedeb;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(\DateTimeInterface $datefin): self
    {
        $this->datefin = $datefin;

        return $this;
    }

    /**
     * @return Collection|Participation[]
     */
    public function getParticipations(): Collection
    {
        return $this->participations;
    }

    public function addParticipation(Participation $participation): self
    {
        if (!$this->participations->contains($participation)) {
            $this->participations[] = $participation;
            $participation->setOffre($this);
        }

        return $this;
    }

    public function removeParticipation(Participation $participation): self
    {
        if ($this->participations->removeElement($participation)) {
            // set the owning side to null (unless already changed)
            if ($participation->getOffre() === $this) {
                $participation->setOffre(null);
            }
        }

        return $this;
    }








}

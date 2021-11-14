<?php

namespace App\Entity;
use App\Entity\User;
use App\Entity\Postlike;
use App\Repository\CommentaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CommentaireRepository::class)
 */
class Commentaire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;



    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\NotBlank(message="le type est obligatoire")
     */
    private $message;

    /**
     * @ORM\OneToMany(targetEntity=Offre::class, mappedBy="commentaire")
     */
    private $offres;

    /**
     * @ORM\ManyToOne(targetEntity=Offre::class, inversedBy="commentaires")
     */
    private $offre;

    /**
     * @ORM\OneToMany(targetEntity=Postlike::class, mappedBy="post",cascade={"all"},orphanRemoval=true)
     */
    private $likes;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datetime;



    public function __construct()
    {
        $this->offres = new ArrayCollection();
        $this->likes = new ArrayCollection();
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
    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return Collection|Offre[]
     */
    public function getOffres(): Collection
    {
        return $this->offres;
    }

    public function addOffre(Offre $offre): self
    {
        if (!$this->offres->contains($offre)) {
            $this->offres[] = $offre;
            $offre->setCommentaire($this);
        }

        return $this;
    }

    public function removeOffre(Offre $offre): self
    {
        if ($this->offres->removeElement($offre)) {
            // set the owning side to null (unless already changed)
            if ($offre->getCommentaire() === $this) {
                $offre->setCommentaire(null);
            }
        }

        return $this;
    }

    public function getOffre(): ?Offre
    {
        return $this->offre;
    }

    public function setOffre(?Offre $offre): self
    {
        $this->offre = $offre;

        return $this;
    }

    /**
     * @return Collection|Postlike[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Postlike $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setPost($this);
        }

        return $this;
    }

    public function removeLike(Postlike $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getPost() === $this) {
                $like->setPost(null);
            }
        }

        return $this;
    }

    /**
     * permet de savaoir si cet article est like par un utilisateur
     * @param User $user
     * @return boolean
     */
    public function islikedByUser(User $user ):bool{
        foreach ($this->likes as $like)
        {
            if ($like->getUser() == $user)
                return true;
        }
        return false;

    }

    public function getDatetime(): ?\DateTimeInterface
    {
        return $this->datetime;
    }

    public function setDatetime(?\DateTimeInterface $datetime): self
    {
        $this->datetime = $datetime;

        return $this;
    }


}

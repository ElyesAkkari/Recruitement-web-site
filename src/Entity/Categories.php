<?php

namespace App\Entity;

use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoriesRepository::class)
 */
class Categories
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=250)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Formation::class, mappedBy="categories")
     */
    private $idformation;

    public function __construct()
    {
        $this->idformation = new ArrayCollection();
    }





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

    /**
     * @return Collection|Formation[]
     */
    public function getIdformation(): Collection
    {
        return $this->idformation;
    }

    public function addIdformation(Formation $idformation): self
    {
        if (!$this->idformation->contains($idformation)) {
            $this->idformation[] = $idformation;
            $idformation->setCategories($this);
        }

        return $this;
    }

    public function removeIdformation(Formation $idformation): self
    {
        if ($this->idformation->removeElement($idformation)) {
            // set the owning side to null (unless already changed)
            if ($idformation->getCategories() === $this) {
                $idformation->setCategories(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->nom ;
    }





}






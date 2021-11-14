<?php

namespace App\Entity;

use App\Repository\OffreserchRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OffreserchRepository::class)
 */
class Offreserch
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sdep;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSdep(): ?string
    {
        return $this->sdep;
    }

    public function setSdep(string $sdep): self
    {
        $this->sdep = $sdep;

        return $this;
    }
}

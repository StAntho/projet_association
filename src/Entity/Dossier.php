<?php

namespace App\Entity;

use App\Repository\DossierRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DossierRepository::class)
 */
class Dossier
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Animal::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $animal;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adoptionfile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $identitycard;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="dossiers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnimal(): ?Animal
    {
        return $this->animal;
    }

    public function setAnimal(?Animal $animal): self
    {
        $this->animal = $animal;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getAdoptionfile()
    {
        return $this->adoptionfile;
    }

    public function setAdoptionfile(string $adoptionfile): self
    {
        $this->adoptionfile = $adoptionfile;

        return $this;
    }

    public function getIdentitycard()
    {
        return $this->identitycard;
    }

    public function setIdentitycard(string $identitycard): self
    {
        $this->identitycard = $identitycard;

        return $this;
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

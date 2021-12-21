<?php

namespace App\Entity;

use App\Repository\AnimalRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnimalRepository::class)
 */
class Animal
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
    private $Name;

    /**
     * @ORM\Column(type="integer")
     */
    private $Age;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Race;

    /**
     * @ORM\Column(type="text")
     */
    private $Description;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $Gender;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Sterilised;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Reserved;

    /**
     * @ORM\Column(type="datetime")
     */
    private $Date_arrived;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->Age;
    }

    public function setAge(int $Age): self
    {
        $this->Age = $Age;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->Type;
    }

    public function setType(string $Type): self
    {
        $this->Type = $Type;

        return $this;
    }

    public function getRace(): ?string
    {
        return $this->Race;
    }

    public function setRace(string $Race): self
    {
        $this->Race = $Race;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->Gender;
    }

    public function setGender(string $Gender): self
    {
        $this->Gender = $Gender;

        return $this;
    }

    public function getSterilised(): ?bool
    {
        return $this->Sterilised;
    }

    public function setSterilised(bool $Sterilised): self
    {
        $this->Sterilised = $Sterilised;

        return $this;
    }

    public function getReserved(): ?bool
    {
        return $this->Reserved;
    }

    public function setReserved(bool $Reserved): self
    {
        $this->Reserved = $Reserved;

        return $this;
    }

    public function getDateArrived(): ?\DateTimeInterface
    {
        return $this->Date_arrived;
    }

    public function setDateArrived(\DateTimeInterface $Date_arrived): self
    {
        $this->Date_arrived = $Date_arrived;

        return $this;
    }
}

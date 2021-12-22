<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
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

     #[
        Assert\NotBlank(
            message: "le {{label}} ne peut pas être vide, merci de le remplir"
        ),
        Assert\Length(
            min: 3,
            max: 20,
            minMessage: "Le genre doit contenir au minimum {{ limit }} caractères",
            maxMessage: "Le genre doit contenir au maximum {{ limit }} caractères"
        )
    ]
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[
        Assert\NotBlank(
            message: "le {{label}} ne peut pas être vide, merci de le remplir"
        ),
        Assert\Length(
            min: 3,
            max: 20,
            minMessage: "Le genre doit contenir au minimum {{ limit }} caractères",
            maxMessage: "Le genre doit contenir au maximum {{ limit }} caractères"
        )
    ]

    private $description;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    #[
        Assert\NotBlank(
            message: "le {{label}} ne peut pas être vide, merci de le remplir"
        ),
        Assert\Positive(
            message: 'La valeur {{value}} saisie est invalide, merci de rentrer une valeur positive superieur à 0'
        )
    ]
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    #[
        Assert\NotBlank(
            message: "le {{label}} ne peut pas être vide, merci de le remplir"
        ),
        Assert\Positive(
            message: 'La valeur {{value}} saisie est invalide, merci de rentrer une valeur positive superieur à 0'
        )
    ]
    
    private $quantity;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */

    #[
        Assert\File(
            mimeTypes: ["image/png", "image/jpeg"],
            mimeTypesMessage: "On attend un fichier JPG ou PNG"
        )
    ]
    private $image;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->kind = $name;

        return $this;
    }

    public function getdescription(): ?string
    {
        return $this->description;
    }

    public function setdescription(string $description): self
    {
        $this->type = $description;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }
}

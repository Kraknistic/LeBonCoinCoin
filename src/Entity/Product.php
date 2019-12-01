<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $state;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="datetime")
     */
    private $publishdate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\UserLogin", inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ImageProduct", mappedBy="product", orphanRemoval=true)
     */
    private $imageProducts;

    public function __construct()
    {
        $this->imageProducts = new ArrayCollection();
    }

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
        $this->name = $name;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getPublishdate(): ?\DateTimeInterface
    {
        return $this->publishdate;
    }

    public function setPublishdate(\DateTimeInterface $publishdate): self
    {
        $this->publishdate = $publishdate;

        return $this;
    }

    public function getUser(): ?UserLogin
    {
        return $this->user;
    }

    public function setUser(?UserLogin $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|ImageProduct[]
     */
    public function getImageProducts(): Collection
    {
        return $this->imageProducts;
    }

    public function addImageProduct(ImageProduct $imageProduct): self
    {
        if (!$this->imageProducts->contains($imageProduct)) {
            $this->imageProducts[] = $imageProduct;
            $imageProduct->setProduct($this);
        }

        return $this;
    }

    public function removeImageProduct(ImageProduct $imageProduct): self
    {
        if ($this->imageProducts->contains($imageProduct)) {
            $this->imageProducts->removeElement($imageProduct);
            // set the owning side to null (unless already changed)
            if ($imageProduct->getProduct() === $this) {
                $imageProduct->setProduct(null);
            }
        }

        return $this;
    }
}
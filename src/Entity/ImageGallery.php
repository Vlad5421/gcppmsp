<?php

namespace App\Entity;

use App\Repository\ImageGalleryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImageGalleryRepository::class)]
class ImageGallery
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?int $imageCollection = null;

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

    public function getImageCollection(): ?int
    {
        return $this->imageCollection;
    }

    public function setImageCollection(?int $imageCollection): self
    {
        $this->imageCollection = $imageCollection;

        return $this;
    }
}

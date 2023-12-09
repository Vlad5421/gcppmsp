<?php

namespace App\Entity;

use App\Repository\CollectionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CollectionsRepository::class)]
class Collections
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\OneToMany(mappedBy: 'collection', targetEntity: Filial::class)]
    private $filials;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $type = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'collections')]
    private ?self $collection = null;

    #[ORM\OneToMany(mappedBy: 'collection', targetEntity: self::class)]
    private Collection $collections;

    public function __construct()
    {
        $this->filials = new ArrayCollection();
        $this->collections = new ArrayCollection();
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

    /**
     * @return Collection<int, Filial>
     */
    public function getFilials(): Collection
    {
        return $this->filials;
    }

    public function addFilial(Filial $filial): self
    {
        if (!$this->filials->contains($filial)) {
            $this->filials[] = $filial;
            $filial->setCollection($this);
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    public function removeFilial(Filial $filial): self
    {
        if ($this->filials->removeElement($filial)) {
            // set the owning side to null (unless already changed)
            if ($filial->getCollection() === $this) {
                $filial->setCollection(null);
            }
        }

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCollection(): ?self
    {
        return $this->collection;
    }

    public function setCollection(?self $collection): self
    {
        $this->collection = $collection;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getCollections(): Collection
    {
        return $this->collections;
    }

    public function addCollection(self $collection): self
    {
        if (!$this->collections->contains($collection)) {
            $this->collections->add($collection);
            $collection->setCollection($this);
        }

        return $this;
    }

    public function removeCollection(self $collection): self
    {
        if ($this->collections->removeElement($collection)) {
            // set the owning side to null (unless already changed)
            if ($collection->getCollection() === $this) {
                $collection->setCollection(null);
            }
        }

        return $this;
    }
}

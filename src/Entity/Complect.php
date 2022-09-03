<?php

namespace App\Entity;

use App\Repository\ComplectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Entity
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 */
#[ORM\Entity(repositoryClass: ComplectRepository::class)]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false, hardDelete: true)]
class Complect
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Filial::class, inversedBy: 'complects')]
    #[ORM\JoinColumn(nullable: false)]
    private $filial;

    #[ORM\ManyToOne(targetEntity: Service::class, inversedBy: 'complects')]
    #[ORM\JoinColumn(nullable: false)]
    private $service;

    #[ORM\ManyToOne(targetEntity: Schedule::class, inversedBy: 'complects')]
    #[ORM\JoinColumn(nullable: false)]
    private $schedule;

    #[ORM\OneToMany(mappedBy: 'complect', targetEntity: Card::class)]
    private $cards;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $name;

    #[ORM\OneToMany(mappedBy: 'Complect', targetEntity: UserComplectReference::class, orphanRemoval: true)]
    private $userComplectReferences;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="deletedAt", type="datetime", nullable=true)
     */
    #[ORM\Column(name: 'deletedAt', type: Types::DATETIME_MUTABLE, nullable: true)]
    private $deletedAt;

    public function __construct()
    {
        $this->cards = new ArrayCollection();
        $this->userComplectReferences = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilial(): ?Filial
    {
        return $this->filial;
    }

    public function setFilial(?Filial $filial): self
    {
        $this->filial = $filial;

        return $this;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): self
    {
        $this->service = $service;

        return $this;
    }

    public function getSchedule(): ?Schedule
    {
        return $this->schedule;
    }

    public function setSchedule(?Schedule $schedule): self
    {
        $this->schedule = $schedule;

        return $this;
    }

    /**
     * @return Collection<int, Card>
     */
    public function getCards(): Collection
    {
        return $this->cards;
    }

    public function addCard(Card $card): self
    {
        if (!$this->cards->contains($card)) {
            $this->cards[] = $card;
            $card->setComplect($this);
        }

        return $this;
    }

    public function removeCard(Card $card): self
    {
        if ($this->cards->removeElement($card)) {
            // set the owning side to null (unless already changed)
            if ($card->getComplect() === $this) {
                $card->setComplect(null);
            }
        }

        return $this;
    }


    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }
    public function __toString(): string
    {
        return $this->getName();
    }

    /**
     * @return Collection<int, UserComplectReference>
     */
    public function getUserComplectReferences(): Collection
    {
        return $this->userComplectReferences;
    }

    public function addUserComplectReference(UserComplectReference $userComplectReference): self
    {
        if (!$this->userComplectReferences->contains($userComplectReference)) {
            $this->userComplectReferences[] = $userComplectReference;
            $userComplectReference->setComplect($this);
        }

        return $this;
    }

    public function removeUserComplectReference(UserComplectReference $userComplectReference): self
    {
        if ($this->userComplectReferences->removeElement($userComplectReference)) {
            // set the owning side to null (unless already changed)
            if ($userComplectReference->getComplect() === $this) {
                $userComplectReference->setComplect(null);
            }
        }

        return $this;
    }

    public function getDeletedAt(): ?\DateTime
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTime $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }

}

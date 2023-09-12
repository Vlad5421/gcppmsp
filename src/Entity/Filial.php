<?php

namespace App\Entity;

use App\Repository\FilialRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\Timestampable;

#[ORM\Entity(repositoryClass: FilialRepository::class)]
class  Filial
{
    use Timestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $address;

    #[ORM\ManyToOne(targetEntity: Collections::class, inversedBy: 'filials')]
    private $collection;

    #[ORM\OneToMany(mappedBy: 'filial', targetEntity: FilialService::class, orphanRemoval: true)]
    private Collection $filialServices;

    #[ORM\OneToMany(mappedBy: 'filial', targetEntity: Schedule::class, orphanRemoval: true)]
    private Collection $schedules;

    #[ORM\OneToMany(mappedBy: 'filial', targetEntity: Card::class)]
    private Collection $cards;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    public function __construct()
    {
        $this->filialServices = new ArrayCollection();
        $this->schedules = new ArrayCollection();
        $this->cards = new ArrayCollection();
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }


    public function __toString(): string
    {
        return $this->getName();
    }

    public function getCollection(): ?Collections
    {
        return $this->collection;
    }

    public function setCollection(?Collections $collection): self
    {
        $this->collection = $collection;

        return $this;
    }

    /**
     * @return Collection<int, FilialService>
     */
    public function getFilialServices(): Collection
    {
        return $this->filialServices;
    }

    public function addFilialService(FilialService $filialService): self
    {
        if (!$this->filialServices->contains($filialService)) {
            $this->filialServices->add($filialService);
            $filialService->setFilial($this);
        }

        return $this;
    }

    public function removeFilialService(FilialService $filialService): self
    {
        if ($this->filialServices->removeElement($filialService)) {
            // set the owning side to null (unless already changed)
            if ($filialService->getFilial() === $this) {
                $filialService->setFilial(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Schedule>
     */
    public function getSchedules(): Collection
    {
        return $this->schedules;
    }

    public function addSchedule(Schedule $schedule): self
    {
        if (!$this->schedules->contains($schedule)) {
            $this->schedules->add($schedule);
            $schedule->setFilial($this);
        }

        return $this;
    }

    public function removeSchedule(Schedule $schedule): self
    {
        if ($this->schedules->removeElement($schedule)) {
            // set the owning side to null (unless already changed)
            if ($schedule->getFilial() === $this) {
                $schedule->setFilial(null);
            }
        }

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
            $this->cards->add($card);
            $card->setFilial($this);
        }

        return $this;
    }

    public function removeCard(Card $card): self
    {
        if ($this->cards->removeElement($card)) {
            // set the owning side to null (unless already changed)
            if ($card->getFilial() === $this) {
                $card->setFilial(null);
            }
        }

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }
}

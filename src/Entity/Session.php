<?php

namespace App\Entity;

use App\Repository\SessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SessionRepository::class)]
class Session
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string')]
    private $timeStart;

    #[ORM\Column(type: 'string')]
    private $timeEnd;

    #[ORM\Column(type: 'integer')]
    private $rest;

    #[ORM\OneToMany(mappedBy: 'session', targetEntity: Card::class)]
    private $cards;

    #[ORM\OneToMany(mappedBy: 'session', targetEntity: ScheduleComplect::class)]
    private $scheduleComplects;

    public function __construct()
    {
        $this->cards = new ArrayCollection();
        $this->scheduleComplects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTimeStart(): string
    {
        return $this->timeStart;
    }

    public function setTimeStart(string $timeStart): self
    {
        $this->timeStart = $timeStart;

        return $this;
    }

    public function getTimeEnd(): string
    {
        return $this->timeEnd;
    }

    public function setTimeEnd(string $timeEnd): self
    {
        $this->timeEnd = $timeEnd;

        return $this;
    }

    public function getRest(): ?int
    {
        return $this->rest;
    }

    public function setRest(int $rest): self
    {
        $this->rest = $rest;

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
            $card->setSession($this);
        }

        return $this;
    }

    public function removeCard(Card $card): self
    {
        if ($this->cards->removeElement($card)) {
            // set the owning side to null (unless already changed)
            if ($card->getSession() === $this) {
                $card->setSession(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ScheduleComplect>
     */
    public function getScheduleComplects(): Collection
    {
        return $this->scheduleComplects;
    }

    public function addScheduleComplect(ScheduleComplect $scheduleComplect): self
    {
        if (!$this->scheduleComplects->contains($scheduleComplect)) {
            $this->scheduleComplects[] = $scheduleComplect;
            $scheduleComplect->setSession($this);
        }

        return $this;
    }

    public function removeScheduleComplect(ScheduleComplect $scheduleComplect): self
    {
        if ($this->scheduleComplects->removeElement($scheduleComplect)) {
            // set the owning side to null (unless already changed)
            if ($scheduleComplect->getSession() === $this) {
                $scheduleComplect->setSession(null);
            }
        }

        return $this;
    }
}

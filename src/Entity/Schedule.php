<?php

namespace App\Entity;

use App\Repository\ScheduleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScheduleRepository::class)]
class Schedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\OneToMany(mappedBy: 'schedule', targetEntity: Complect::class)]
    private $complects;

    #[ORM\OneToMany(mappedBy: 'schedule', targetEntity: ScheduleComplect::class, orphanRemoval: true)]
    private $scheduleComplects;

    public function __construct()
    {
        $this->sessions = new ArrayCollection();
        $this->complects = new ArrayCollection();
        $this->scheduleComplects = new ArrayCollection();
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
     * @return Collection<int, Complect>
     */
    public function getComplects(): Collection
    {
        return $this->complects;
    }

    public function addComplect(Complect $complect): self
    {
        if (!$this->complects->contains($complect)) {
            $this->complects[] = $complect;
            $complect->setSchedule($this);
        }

        return $this;
    }

    public function removeComplect(Complect $complect): self
    {
        if ($this->complects->removeElement($complect)) {
            // set the owning side to null (unless already changed)
            if ($complect->getSchedule() === $this) {
                $complect->setSchedule(null);
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
            $scheduleComplect->setSchedule($this);
        }

        return $this;
    }

    public function removeScheduleComplect(ScheduleComplect $scheduleComplect): self
    {
        if ($this->scheduleComplects->removeElement($scheduleComplect)) {
            // set the owning side to null (unless already changed)
            if ($scheduleComplect->getSchedule() === $this) {
                $scheduleComplect->setSchedule(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}

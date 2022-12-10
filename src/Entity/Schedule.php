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

    #[ORM\ManyToOne(inversedBy: 'schedules')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Filial $filial = null;

    #[ORM\ManyToOne(inversedBy: 'schedules')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $worker = null;

    #[ORM\OneToMany(mappedBy: 'schedule', targetEntity: ScheduleInterval::class, orphanRemoval: true)]
    private Collection $scheduleIntervals;

    public function __construct()
    {
        $this->scheduleIntervals = new ArrayCollection();
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


    public function __toString(): string
    {
        return $this->getName();
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

    public function getWorker(): ?User
    {
        return $this->worker;
    }

    public function setWorker(?User $worker): self
    {
        $this->worker = $worker;

        return $this;
    }

    /**
     * @return Collection<int, ScheduleInterval>
     */
    public function getScheduleIntervals(): Collection
    {
        return $this->scheduleIntervals;
    }

    public function addScheduleInterval(ScheduleInterval $scheduleInterval): self
    {
        if (!$this->scheduleIntervals->contains($scheduleInterval)) {
            $this->scheduleIntervals->add($scheduleInterval);
            $scheduleInterval->setSchedule($this);
        }

        return $this;
    }

    public function removeScheduleInterval(ScheduleInterval $scheduleInterval): self
    {
        if ($this->scheduleIntervals->removeElement($scheduleInterval)) {
            // set the owning side to null (unless already changed)
            if ($scheduleInterval->getSchedule() === $this) {
                $scheduleInterval->setSchedule(null);
            }
        }

        return $this;
    }
}

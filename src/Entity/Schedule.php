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

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $day = null;

    #[ORM\Column]
    private ?int $start = null;

    #[ORM\Column]
    private ?int $endTime = null;

    public function __construct()
    {
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

    public function getDay(): ?string
    {
        return $this->day;
    }

    public function setDay(?string $day): self
    {
        $this->day = $day;

        return $this;
    }

    public function getStart(): ?int
    {
        return $this->start;
    }

    public function setStart(int $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEndTime(): ?int
    {
        return $this->endTime;
    }

    public function setEndTime(int $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
    }
}

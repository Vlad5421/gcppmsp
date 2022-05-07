<?php

namespace App\Entity;

use App\Repository\ScheduleComplectRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScheduleComplectRepository::class)]
class ScheduleComplect
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Schedule::class, inversedBy: 'scheduleComplects')]
    #[ORM\JoinColumn(nullable: false)]
    private $schedule;

    #[ORM\ManyToOne(targetEntity: Session::class, inversedBy: 'scheduleComplects')]
    #[ORM\JoinColumn(nullable: false)]
    private $session;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSession(): ?Session
    {
        return $this->session;
    }

    public function setSession(?Session $session): self
    {
        $this->session = $session;

        return $this;
    }
}

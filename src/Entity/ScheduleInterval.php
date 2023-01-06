<?php

namespace App\Entity;

use App\Repository\ScheduleIntervalRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScheduleIntervalRepository::class)]
class ScheduleInterval
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $day = null;

    #[ORM\Column]
    private ?int $start = null;

    #[ORM\Column]
    private ?int $endTime = null;

    #[ORM\ManyToOne(inversedBy: 'scheduleIntervals')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Schedule $schedule = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $customDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDay(): ?int
    {
        return $this->day;
    }

    public function setDay(int $day): self
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

    public function getSchedule(): ?Schedule
    {
        return $this->schedule;
    }

    public function setSchedule(?Schedule $schedule): self
    {
        $this->schedule = $schedule;

        return $this;
    }

    public function getCustomDate(): ?\DateTimeInterface
    {
        return $this->customDate;
    }

    public function setCustomDate(?\DateTimeInterface $customDate): self
    {
        $this->customDate = $customDate;

        return $this;
    }
}

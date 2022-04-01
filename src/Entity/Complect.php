<?php

namespace App\Entity;

use App\Repository\ComplectRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ComplectRepository::class)]
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
}

<?php

namespace App\Entity;

use App\Repository\FilialServiceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FilialServiceRepository::class)]
class FilialService
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'filialServices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Filial $filial = null;

    #[ORM\ManyToOne(inversedBy: 'filialServices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Service $service = null;

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
}

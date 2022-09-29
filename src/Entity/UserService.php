<?php

namespace App\Entity;

use App\Repository\UserServiceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserServiceRepository::class)]
class UserService
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userServices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $worker = null;

    #[ORM\ManyToOne(inversedBy: 'userServices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Service $service = null;

    public function getId(): ?int
    {
        return $this->id;
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

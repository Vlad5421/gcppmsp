<?php

namespace App\Entity;

use App\Repository\UserComplectReferenceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserComplectReferenceRepository::class)]
class UserComplectReference
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'userComplectReferences')]
    #[ORM\JoinColumn(nullable: false)]
    private $worker;

    #[ORM\ManyToOne(targetEntity: Complect::class, inversedBy: 'userComplectReferences')]
    #[ORM\JoinColumn(nullable: false)]
    private $Complect;

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

    public function getComplect(): ?Complect
    {
        return $this->Complect;
    }

    public function setComplect(?Complect $Complect): self
    {
        $this->Complect = $Complect;

        return $this;
    }
}

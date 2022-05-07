<?php

namespace App\Entity;

use App\Repository\CardRepository;
use App\Repository\ComplectRepository;
use App\Repository\SessionRepository;
use App\Repository\UserRepository;
use App\Services\ScheduleMaker;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CardRepository::class)]
class Card
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Complect::class, inversedBy: 'cards')]
    #[ORM\JoinColumn(nullable: false)]
    private $complect;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'cards')]
    #[ORM\JoinColumn(nullable: false)]
    private $specialist;

    #[ORM\ManyToOne(targetEntity: Session::class, inversedBy: 'cards')]
    #[ORM\JoinColumn(nullable: false)]
    private $session;

    #[ORM\Column(type: 'date', nullable: true)]
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComplect(): ?Complect
    {
        return $this->complect;
    }

    public function setComplect(?Complect $complect): self
    {
        $this->complect = $complect;

        return $this;
    }

    public function getSpecialist(): ?User
    {
        return $this->specialist;
    }

    public function setSpecialist(?User $specialist): self
    {
        $this->specialist = $specialist;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
//    public function setDate(string $date): self
    {
        $this->date = $date;

        return $this;
    }
}

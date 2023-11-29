<?php

namespace App\Entity;

use App\Repository\VisitorRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VisitorRepository::class)]
class Visitor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $email;

    #[ORM\Column(type: 'string', length: 255)]
    private $phoneNumber;

    #[ORM\Column(type: 'string', length: 2)]
    private $ageChildren;

    #[ORM\Column(type: 'text', nullable: true)]
    private $reason;

    #[ORM\Column(type: 'string', length: 255)]
    private $consultForm;

    #[ORM\Column(type: 'boolean')]
    private $consent;

    #[ORM\ManyToOne(targetEntity: Card::class, inversedBy: 'visitors')]
    #[ORM\JoinColumn(nullable: false)]
    private $card;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getAgeChildren(): ?string
    {
        return $this->ageChildren;
    }

    public function setAgeChildren(string $ageChildren): self
    {
        $this->ageChildren = $ageChildren;

        return $this;
    }

    public function getReason(): ?string
    {
        if ($this->reason) {
            return $this->reason;
        }
        return "Причина не описана";
    }
    public function setReason(?string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    public function getConsultForm(): ?string
    {
        return $this->consultForm;
    }

    public function setConsultForm(string $consultForm): self
    {
        $this->consultForm = $consultForm;

        return $this;
    }

    public function getConsent(): ?bool
    {
        return $this->consent;
    }

    public function setConsent(bool $consent): self
    {
        $this->consent = $consent;

        return $this;
    }

    public function getCard(): ?Card
    {
        return $this->card;
    }

    public function setCard(?Card $card): self
    {
        $this->card = $card;

        return $this;
    }
}

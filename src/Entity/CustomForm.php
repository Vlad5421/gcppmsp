<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CustomFormRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: CustomFormRepository::class)]
#[ORM\Table(name: 'custom_forms')]
class CustomForm
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    private bool $isActive = true;

    #[ORM\Column(type: 'json')]
    private array $structure = [];

    #[ORM\Column(type: 'datetime')]
    private DateTimeInterface $createdAt;

    #[ORM\Column(type: 'datetime')]
    private DateTimeInterface $updatedAt;

    #[ORM\OneToMany(targetEntity: FormSubmission::class, mappedBy: 'form', cascade: ['persist', 'remove'])]
    private Collection $submissions;

    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
        $this->submissions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function isIsActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getStructure(): array
    {
        return $this->structure;
    }

    public function setStructure(array $structure): self
    {
        $this->structure = $structure;

        return $this;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getSubmissions(): Collection
    {
        return $this->submissions;
    }

    public function addSubmission(FormSubmission $submission): self
    {
        if (!$this->submissions->contains($submission)) {
            $this->submissions->add($submission);
            $submission->setForm($this);
        }

        return $this;
    }

    public function removeSubmission(FormSubmission $submission): self
    {
        if ($this->submissions->removeElement($submission)) {
            // set the owning side to null (unless already changed)
            if ($submission->getForm() === $this) {
                $submission->setForm(null);
            }
        }

        return $this;
    }
}
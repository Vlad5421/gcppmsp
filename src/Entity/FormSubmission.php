<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\FormSubmissionRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use DateTimeInterface;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: FormSubmissionRepository::class)]
#[ORM\Table(name: 'form_submissions')]
class FormSubmission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: CustomForm::class, inversedBy: 'submissions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Ignore]
    private CustomForm $form;

    #[ORM\Column(type: 'json')]
    private array $data = [];

    #[ORM\Column(type: 'datetime')]
    private DateTimeInterface $submittedAt;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $userId = null;

    public function __construct()
    {
        $this->submittedAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getForm(): CustomForm
    {
        return $this->form;
    }

    public function setForm(CustomForm $form): self
    {
        $this->form = $form;

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getSubmittedAt(): DateTimeInterface
    {
        return $this->submittedAt;
    }

    public function setSubmittedAt(DateTimeInterface $submittedAt): self
    {
        $this->submittedAt = $submittedAt;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }
}
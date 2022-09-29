<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\Timestampable;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
class Service
{
    use Timestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $price;

    #[ORM\Column(type: 'integer')]
    private $duration;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $serviceLogo;

    #[ORM\OneToMany(mappedBy: 'service', targetEntity: Complect::class)]
    private $complects;

    #[ORM\OneToMany(mappedBy: 'service', targetEntity: UserService::class)]
    private Collection $userServices;

    #[ORM\OneToMany(mappedBy: 'service', targetEntity: FilialService::class, orphanRemoval: true)]
    private Collection $filialServices;

    public function __construct()
    {
        $this->complects = new ArrayCollection();
        $this->userServices = new ArrayCollection();
        $this->filialServices = new ArrayCollection();
    }

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

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getServiceLogo(): ?string
    {
        return $this->serviceLogo;
    }

    public function setServiceLogo(?string $serviceLogo): self
    {
        $this->serviceLogo = $serviceLogo;

        return $this;
    }

    /**
     * @return Collection<int, Complect>
     */
    public function getComplects(): Collection
    {
        return $this->complects;
    }

    public function addComplect(Complect $complect): self
    {
        if (!$this->complects->contains($complect)) {
            $this->complects[] = $complect;
            $complect->setService($this);
        }

        return $this;
    }

    public function removeComplect(Complect $complect): self
    {
        if ($this->complects->removeElement($complect)) {
            // set the owning side to null (unless already changed)
            if ($complect->getService() === $this) {
                $complect->setService(null);
            }
        }

        return $this;
    }
    public function __toString(): string
    {
        return $this->getName();
    }

    /**
     * @return Collection<int, UserService>
     */
    public function getUserServices(): Collection
    {
        return $this->userServices;
    }

    public function addUserService(UserService $userService): self
    {
        if (!$this->userServices->contains($userService)) {
            $this->userServices->add($userService);
            $userService->setService($this);
        }

        return $this;
    }

    public function removeUserService(UserService $userService): self
    {
        if ($this->userServices->removeElement($userService)) {
            // set the owning side to null (unless already changed)
            if ($userService->getService() === $this) {
                $userService->setService(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FilialService>
     */
    public function getFilialServices(): Collection
    {
        return $this->filialServices;
    }

    public function addFilialService(FilialService $filialService): self
    {
        if (!$this->filialServices->contains($filialService)) {
            $this->filialServices->add($filialService);
            $filialService->setService($this);
        }

        return $this;
    }

    public function removeFilialService(FilialService $filialService): self
    {
        if ($this->filialServices->removeElement($filialService)) {
            // set the owning side to null (unless already changed)
            if ($filialService->getService() === $this) {
                $filialService->setService(null);
            }
        }

        return $this;
    }
}

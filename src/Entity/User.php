<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $FIO;

    #[ORM\Column(type: 'string', length: 255)]
    private $email;

    #[ORM\Column(type: 'string', length: 255)]
    private $password;

    #[ORM\OneToMany(mappedBy: 'specialist', targetEntity: Card::class)]
    private $cards;

    #[ORM\OneToMany(mappedBy: 'specialist', targetEntity: Complect::class)]
    private $complects;

    #[ORM\ManyToOne(targetEntity: Complect::class, inversedBy: 'users')]
    private $complect;


    #[ORM\Column(type:"json")]
    private $roles = [];

    #[ORM\OneToMany(mappedBy: 'autor', targetEntity: Article::class)]
    private $articles;

    public function __construct()
    {
        $this->cards = new ArrayCollection();
        $this->complects = new ArrayCollection();
        $this->articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFIO(): ?string
    {
        return $this->FIO;
    }

    public function setFIO(string $FIO): self
    {
        $this->FIO = $FIO;

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

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection<int, Card>
     */
    public function getCards(): Collection
    {
        return $this->cards;
    }

    public function addCard(Card $card): self
    {
        if (!$this->cards->contains($card)) {
            $this->cards[] = $card;
            $card->setSpecialist($this);
        }

        return $this;
    }

    public function removeCard(Card $card): self
    {
        if ($this->cards->removeElement($card)) {
            // set the owning side to null (unless already changed)
            if ($card->getSpecialist() === $this) {
                $card->setSpecialist(null);
            }
        }

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
            $complect->setSpecialist($this);
        }

        return $this;
    }

    public function removeComplect(Complect $complect): self
    {
        if ($this->complects->removeElement($complect)) {
            // set the owning side to null (unless already changed)
            if ($complect->getSpecialist() === $this) {
                $complect->setSpecialist(null);
            }
        }

        return $this;
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

    /**
     * @return array
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param array $roles
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setAutor($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getAutor() === $this) {
                $article->setAutor(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getFIO();
    }
}

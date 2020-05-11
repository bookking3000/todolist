<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="Email", message="Ein Konto mit dieser E-Mail-Adresse ist bereits registriert.")
 * @UniqueEntity(fields="Username", message="Der gewählte Benutzername wurde bereits vergeben.")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isValidated;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $Username;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()

     */
    private $Email;

    /**
     * @ORM\Column(type="datetime")
     */
    private $RegistrationDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $LastLoginTime;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Todo", mappedBy="Owner", orphanRemoval=true)
     */
    private $ownedTodos;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Todo", mappedBy="Contributors")
     */
    private $todosContributingTo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Password;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;


    public function __construct()
    {
        $this->ownedTodos = new ArrayCollection();
        $this->todosContributingTo = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsValidated(): ?bool
    {
        return $this->isValidated;
    }

    public function setIsValidated(bool $isValidated): self
    {
        $this->isValidated = $isValidated;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->Username;
    }

    public function setUsername(string $Username): self
    {
        $this->Username = $Username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): self
    {
        $this->Email = $Email;

        return $this;
    }

    public function getRegistrationDate(): ?\DateTimeInterface
    {
        return $this->RegistrationDate;
    }

    public function setRegistrationDate(\DateTimeInterface $RegistrationDate): self
    {
        $this->RegistrationDate = $RegistrationDate;

        return $this;
    }

    public function getLastLoginTime(): ?\DateTimeInterface
    {
        return $this->LastLoginTime;
    }

    public function setLastLoginTime(?\DateTimeInterface $LastLoginTime): self
    {
        $this->LastLoginTime = $LastLoginTime;

        return $this;
    }

    /**
     * @return Collection|Todo[]
     */
    public function getOwnedTodos(): Collection
    {
        return $this->ownedTodos;
    }

    public function addOwnedTodo(Todo $ownedTodo): self
    {
        if (!$this->ownedTodos->contains($ownedTodo)) {
            $this->ownedTodos[] = $ownedTodo;
            $ownedTodo->setOwner($this);
        }

        return $this;
    }

    public function removeOwnedTodo(Todo $ownedTodo): self
    {
        if ($this->ownedTodos->contains($ownedTodo)) {
            $this->ownedTodos->removeElement($ownedTodo);
            // set the owning side to null (unless already changed)
            if ($ownedTodo->getOwner() === $this) {
                $ownedTodo->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Todo[]
     */
    public function getTodosContributingTo(): Collection
    {
        return $this->todosContributingTo;
    }

    public function addTodosContributingTo(Todo $todosContributingTo): self
    {
        if (!$this->todosContributingTo->contains($todosContributingTo)) {
            $this->todosContributingTo[] = $todosContributingTo;
            $todosContributingTo->addContributor($this);
        }

        return $this;
    }

    public function removeTodosContributingTo(Todo $todosContributingTo): self
    {
        if ($this->todosContributingTo->contains($todosContributingTo)) {
            $this->todosContributingTo->removeElement($todosContributingTo);
            $todosContributingTo->removeContributor($this);
        }

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->Password;
    }

    public function setPassword(string $Password): self
    {
        $this->Password = $Password;

        return $this;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return array('ROLE_USER');
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    public function getDisplayName()
    {
        return $this->getUsername().' ('.$this->getEmail().')';
    }

    public function __toString()
    {
        return $this->getUsername();
    }

}

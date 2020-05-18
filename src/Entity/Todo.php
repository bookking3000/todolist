<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\TodoRepository")
 */
class Todo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     */
    private $Name;

    /**
     * @Assert\Type("string")
     * @ORM\Column(type="string", length=4096, nullable=true)
     */
    private $Description;

    /**
     * @Assert\Type("\DateTime")
     * @ORM\Column(type="datetime")
     */
    private $CreationDate;

    /**
     * @Assert\NotBlank
     * @Assert\Type("\DateTime")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $DueDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="ownedTodos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Owner;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="todosContributingTo")
     */
    private $Contributors;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $CompletionDate;

    /**
     * @ORM\ManyToMany(targetEntity=TodoCategory::class, inversedBy="todos")
     */
    private $Category;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isArchived;

    public function __construct()
    {
        $this->Contributors = new ArrayCollection();
        $this->Category = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->CreationDate;
    }

    public function setCreationDate(\DateTimeInterface $CreationDate): self
    {
        $this->CreationDate = $CreationDate;

        return $this;
    }

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->DueDate;
    }

    public function setDueDate(?\DateTimeInterface $DueDate): self
    {
        $this->DueDate = $DueDate;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->Owner;
    }

    public function setOwner(?User $Owner): self
    {
        $this->Owner = $Owner;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getContributors(): Collection
    {
        return $this->Contributors;
    }

    public function addContributor(User $contributor): self
    {
        if (!$this->Contributors->contains($contributor)) {
            $this->Contributors[] = $contributor;
        }

        return $this;
    }

    public function removeContributor(User $contributor): self
    {
        if ($this->Contributors->contains($contributor)) {
            $this->Contributors->removeElement($contributor);
        }

        return $this;
    }

    public function getCompletionDate(): ?\DateTimeInterface
    {
        return $this->CompletionDate;
    }

    public function setCompletionDate(?\DateTimeInterface $CompletionDate): self
    {
        $this->CompletionDate = $CompletionDate;

        return $this;
    }

    /**
     * @return Collection|TodoCategory[]
     */
    public function getCategory(): Collection
    {
        return $this->Category;
    }

    public function addCategory(TodoCategory $category): self
    {
        if (!$this->Category->contains($category)) {
            $this->Category[] = $category;
        }

        return $this;
    }

    public function removeCategory(TodoCategory $category): self
    {
        if ($this->Category->contains($category)) {
            $this->Category->removeElement($category);
        }

        return $this;
    }

    public function getIsArchived(): ?bool
    {
        return $this->isArchived;
    }

    public function setIsArchived(bool $isArchived): self
    {
        $this->isArchived = $isArchived;

        return $this;
    }
}

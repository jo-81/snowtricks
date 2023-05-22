<?php

namespace App\Entity;

use App\Repository\CommentSignaledRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentSignaledRepository::class)]
#[ORM\HasLifecycleCallbacks]
class CommentSignaled
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $reason = null;

    #[ORM\OneToOne(inversedBy: 'commentSignaled')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Comment $comment = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $editedAt = null;

    #[ORM\Column]
    private ?bool $valided = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    public function getComment(): ?Comment
    {
        return $this->comment;
    }

    public function setComment(Comment $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getEditedAt(): ?\DateTimeImmutable
    {
        return $this->editedAt;
    }

    public function setEditedAt(\DateTimeImmutable $editedAt): self
    {
        $this->editedAt = $editedAt;

        return $this;
    }

    public function isValided(): ?bool
    {
        return $this->valided;
    }

    public function setValided(bool $valided): self
    {
        $this->valided = $valided;

        return $this;
    }

    #[ORM\PrePersist]
    public function setValueWhenPersist(): void
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->editedAt = new \DateTimeImmutable();
        $this->valided = false;
    }

    #[ORM\PreUpdate]
    public function setValueWhenUpdate(): void
    {
        $this->editedAt = new \DateTimeImmutable();
    }
}

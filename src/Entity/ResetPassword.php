<?php

namespace App\Entity;

use App\Repository\ResetPasswordRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ResetPasswordRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity('person')]
class ResetPassword
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $expiredAt = null;

    #[ORM\Column(length: 255)]
    private ?string $token = null;

    #[ORM\OneToOne()]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?User $person = null;

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

    public function getExpiredAt(): ?\DateTimeImmutable
    {
        return $this->expiredAt;
    }

    public function setExpiredAt(\DateTimeImmutable $expiredAt): self
    {
        $this->expiredAt = $expiredAt;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getPerson(): ?User
    {
        return $this->person;
    }

    public function setPerson(User $person): self
    {
        $this->person = $person;

        return $this;
    }

    #[ORM\PrePersist]
    public function setValueWhenPersist(): void
    {
        $date = new \DateTimeImmutable();
        $expiredDate = $date->add(new \DateInterval('P1D'));

        $this->createdAt = $date;
        $this->expiredAt = $expiredDate;
    }
}

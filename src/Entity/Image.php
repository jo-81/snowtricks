<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
#[Vich\Uploadable]
class Image extends Media
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    protected ?string $alt = null;

    #[Assert\Image(
        maxWidth: 150,
        maxHeight: 150,
    )]
    #[Assert\File(
        extensions: ['png', 'jpg'],
        extensionsMessage: "Le type de l'image n'est pas valide. Seulement les types .png et .jpg sont autorisés."
    )]
    #[Vich\UploadableField(mapping: 'avatar', fileNameProperty: 'path')]
    private ?File $imageFile = null;

    #[Assert\Image(
        maxWidth: 1440,
    )]
    #[Assert\File(
        extensions: ['png', 'jpg', 'jpeg'],
        extensionsMessage: "Le type de l'image n'est pas valide. Seulement les types .png, .jpg et .jpeg sont autorisés."
    )]
    #[Vich\UploadableField(mapping: 'trick', fileNameProperty: 'path')]
    private ?File $trickFile = null;

    #[ORM\Column(nullable: true)]
    /** @phpstan-ignore-next-line */
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private ?Trick $trick = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(?string $alt): self
    {
        $this->alt = $alt;

        return $this;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function setTrickFile(?File $trickFile = null): void
    {
        $this->trickFile = $trickFile;

        if (null !== $trickFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getTrickFile(): ?File
    {
        return $this->trickFile;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function getTrick(): ?Trick
    {
        return $this->trick;
    }

    public function setTrick(?Trick $trick): self
    {
        $this->trick = $trick;

        return $this;
    }
}

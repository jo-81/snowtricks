<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

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
    #[Assert\file(
        extensions: ['png', 'jpg'],
        extensionsMessage: "Le type de l'image n'est pas valide. Seulement les types .png et .jpg sont autorisés."
    )]
    #[Vich\UploadableField(mapping: 'avatar', fileNameProperty: 'path')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

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

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function __toString()
    {
        return $this->path;
    }

    public function __serialize()
    {
        return [
            $this->id,
            $this->path,
        ];
    }

    public function __unserialize($serialized)
    {
        list($this->id, $this->path) = unserialize(serialize($serialized));
    }
}

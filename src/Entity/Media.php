<?php

namespace App\Entity;

use App\Repository\MediaRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\InheritanceType;
use Symfony\Component\Serializer\Annotation\DiscriminatorMap;

#[ORM\Entity(repositoryClass: MediaRepository::class)]
#[InheritanceType('JOINED')]
#[DiscriminatorColumn(
    name: 'discr',
    type: 'string')
]
#[DiscriminatorMap(
    typeProperty: 'discr',
    mapping: ['media' => 'Media', 'image' => 'Image', 'video' => 'Video'])
]
abstract class Media
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    protected ?string $path = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function __toString()
    {
        return is_null($this->path) ? '' : $this->path;
    }

    public function __serialize()
    {
        return [
            $this->id,
            $this->path,
        ];
    }

    /**
     * __unserialize.
     *
     * @param array<mixed> $serialized
     *
     * @return void
     */
    public function __unserialize(array $serialized)
    {
        list($this->id, $this->path) = unserialize(serialize($serialized)); /* @phpstan-ignore-line */
    }
}

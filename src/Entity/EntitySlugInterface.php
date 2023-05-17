<?php

namespace App\Entity;

interface EntitySlugInterface
{
    public function getSlug(): ?string;

    public function setSlug(string $slug): self;

    public function targetField(): string;
}

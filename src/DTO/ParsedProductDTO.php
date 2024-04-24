<?php

namespace App\DTO;

use App\Contract\DTO;

class ParsedProductDTO implements DTO
{
    protected string $name;
    protected string $imageUrl;

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setImageUrl(string $url): static
    {
        $this->imageUrl = $url;
        return $this;
    }

    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }
}

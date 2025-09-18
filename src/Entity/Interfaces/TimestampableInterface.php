<?php

namespace App\Entity\Interfaces;

interface TimestampableInterface
{
    public function getCreatedAt(): ?\DateTimeInterface;
    public function setCreatedAt(\DateTimeInterface $dt): self;
    public function getUpdatedAt(): ?\DateTimeInterface;
    public function setUpdatedAt(\DateTimeInterface $dt): self;
}

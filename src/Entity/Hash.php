<?php

namespace App\Entity;

use App\Repository\HashRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HashRepository::class)]
class Hash
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: "datetime")]
    private $batch;

    #[ORM\Column(type: 'string', length: 100)]
    private $string_input;

    #[ORM\Column(type: 'string', length: 100)]
    private $key_found;

  /*  #[ORM\Column(type: 'string', length: 100)]
    private $string;*/

    #[ORM\Column(type: 'integer', nullable: 'false')]
    private $number_attempts;

    #[ORM\Column(type: 'string', length: 100)]
    private $hash_generated;

    public function getHashGenerated(): ?string
    {
        return $this->hash_generated;
    }

    public function setHashGenerated(string $hash_generated): self
    {
        $this->hash_generated = $hash_generated;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBatch(): ?\DateTimeInterface
    {
        return $this->batch;
    }

    public function setBatch(\DateTimeInterface $batch): self
    {
        $this->batch = $batch;
        //$this->batch = new \DateTime("now");

        return $this;
    }

    public function getStringInput(): ?string
    {
        return $this->string_input;
    }

    public function setStringInput(string $string_input): self
    {
        $this->string_input = $string_input;

        return $this;
    }

    public function getKeyFound(): ?string
    {
        return $this->key_found;
    }

    public function setKeyFound(string $key_found): self
    {
        $this->key_found = $key_found;

        return $this;
    }

    public function getNumberAttempts(): ?int
    {
        return $this->number_attempts;
    }

    public function setNumberAttempts(int $number_attempts): self
    {
        $this->number_attempts = $number_attempts;

        return $this;
    }
}

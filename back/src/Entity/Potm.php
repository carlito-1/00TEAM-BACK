<?php

namespace App\Entity;

use App\Repository\PotmRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PotmRepository::class)]
class Potm
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $voto = null;


    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Usuario $Usuario = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVoto(): ?string
    {
        return $this->voto;
    }

    public function setVoto(string $voto): static
    {
        $this->voto = $voto;

        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->Usuario;
    }

    public function setUsuario(Usuario $Usuario): static
    {
        $this->Usuario = $Usuario;

        return $this;
    }

}

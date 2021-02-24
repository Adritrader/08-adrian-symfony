<?php

namespace App\Entity;

use App\Repository\RegistraRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RegistraRepository::class)
 */
class Registra
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */

    private $horaCita;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fechaCita;



    public function getId(): ?int
    {
        return $this->id;
    }


    public function getHoraCita(): ?\DateTimeInterface
    {
        return $this->horaCita;
    }

    public function setHoraCita(\DateTimeInterface $horaCita): self
    {
        $this->horaCita = $horaCita;

        return $this;
    }

    public function getFechaCita(): ?\DateTimeInterface
    {
        return $this->fechaCita;
    }

    public function setFechaCita(\DateTimeInterface $fechaCita): self
    {
        $this->fechaCita = $fechaCita;

        return $this;
    }
}

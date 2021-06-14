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
     * @ORM\Column(type="string")
     */

    private $hora;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha;

    /**
     * @ORM\Column(type="integer")
     */
    private $usuario_id;


    /**
     * @ORM\ManyToOne(targetEntity=Servicio::class, inversedBy="registras")
     * @ORM\JoinColumn(nullable=false)
     */
    private $servicio_id;



    public function getId(): ?int
    {
        return $this->id;
    }


    public function getHoraCita(): ?string
    {
        return $this->hora;
    }

    public function setHoraCita(string $hora): self
    {
        $this->hora = $hora;

        return $this;
    }

    public function getFechaCita(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFechaCita(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsuarioId()
    {
        return $this->usuario_id;
    }

    /**
     * @param mixed $usuario_id
     */
    public function setUsuarioId($usuario_id): void
    {
        $this->usuario_id = $usuario_id;
    }


    public function getServicioId(): ?Servicio
    {
        return $this->servicio_id;
    }

    public function setServicioId(?Servicio $servicio_id): self
    {
        $this->servicio_id = $servicio_id;

        return $this;
    }


}

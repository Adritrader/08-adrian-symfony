<?php

namespace App\Entity;

use App\Repository\PedidosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PedidosRepository::class)
 */
class Pedidos
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $precio;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $estado;

    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class, inversedBy="pedidos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    /**
     * @ORM\OneToMany(targetEntity=LineaPedido::class, mappedBy="pedido", orphanRemoval=true)
     */
    private $lineaPedidos;

    /**
     * @ORM\ManyToOne(targetEntity=LineaPedido::class, inversedBy="pedidos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lineaPedido;

    public function __construct()
    {
        $this->lineaPedidos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrecio(): ?float
    {
        return $this->precio;
    }

    public function setPrecio(float $precio): self
    {
        $this->precio = $precio;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * @return Collection|LineaPedido[]
     */
    public function getLineaPedidos(): Collection
    {
        return $this->lineaPedidos;
    }

    public function addLineaPedido(LineaPedido $lineaPedido): self
    {
        if (!$this->lineaPedidos->contains($lineaPedido)) {
            $this->lineaPedidos[] = $lineaPedido;
            $lineaPedido->setPedido($this);
        }

        return $this;
    }

    public function removeLineaPedido(LineaPedido $lineaPedido): self
    {
        if ($this->lineaPedidos->removeElement($lineaPedido)) {
            // set the owning side to null (unless already changed)
            if ($lineaPedido->getPedido() === $this) {
                $lineaPedido->setPedido(null);
            }
        }

        return $this;
    }

    public function getLineaPedido(): ?LineaPedido
    {
        return $this->lineaPedido;
    }

    public function setLineaPedido(?LineaPedido $lineaPedido): self
    {
        $this->lineaPedido = $lineaPedido;

        return $this;
    }
}

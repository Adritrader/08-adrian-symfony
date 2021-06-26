<?php

namespace App\Entity;

use App\Repository\LineaPedidoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LineaPedidoRepository::class)
 */
class LineaPedido
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Producto::class, inversedBy="linePedidos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $producto;

    /**
     * @ORM\ManyToOne(targetEntity=Pedidos::class, inversedBy="lineaPedidos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pedido;

    /**
     * @ORM\OneToMany(targetEntity=Pedidos::class, mappedBy="lineaPedido", orphanRemoval=true)
     */
    private $pedidos;

    public function __construct()
    {
        $this->pedidos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProducto(): ?Producto
    {
        return $this->producto;
    }

    public function setProducto(?Producto $producto): self
    {
        $this->producto = $producto;

        return $this;
    }

    public function getPedido(): ?Pedidos
    {
        return $this->pedido;
    }

    public function setPedido(?Pedidos $pedido): self
    {
        $this->pedido = $pedido;

        return $this;
    }

    /**
     * @return Collection|Pedidos[]
     */
    public function getPedidos(): Collection
    {
        return $this->pedidos;
    }

    public function addPedido(Pedidos $pedido): self
    {
        if (!$this->pedidos->contains($pedido)) {
            $this->pedidos[] = $pedido;
            $pedido->setLineaPedido($this);
        }

        return $this;
    }

    public function removePedido(Pedidos $pedido): self
    {
        if ($this->pedidos->removeElement($pedido)) {
            // set the owning side to null (unless already changed)
            if ($pedido->getLineaPedido() === $this) {
                $pedido->setLineaPedido(null);
            }
        }

        return $this;
    }
}

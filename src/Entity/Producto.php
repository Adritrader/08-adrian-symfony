<?php

namespace App\Entity;

use App\Repository\ProductoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=ProductoRepository::class)
 */
class Producto implements \Serializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank( message = "El nombre es obligatorio")
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank( message = "La categoria es obligatoria")
     */
    private $categoria;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank( message = "La descripción es obligatorio")
     */
    private $descripcion;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank( message = "El precio es obligatorio")
     */
    private $precio;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank( message = "La imagen es obligatoria")
     */
    private $imagen;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getCategoria(): ?string
    {
        return $this->categoria;
    }

    public function setCategoria(string $categoria): self
    {
        $this->categoria = $categoria;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getPrecio(): ?int
    {
        return $this->precio;
    }

    public function setPrecio(int $precio): self
    {
        $this->precio = $precio;

        return $this;
    }

    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function setImagen(string $imagen): self
    {
        $this->imagen = $imagen;

        return $this;
    }

    /**
     * String representation of object.
     * @link https://php.net/manual/en/serializable.serialize.php
     * @return string|null The string representation of the object or null
     * @throws Exception Returning other type than string or null
     */
    public function serialize(): ?string
    {

        return serialize([
            $this->getId(),
            $this->getNombre(),
            $this->getCategoria()
        ]);

        // TODO: Implement serialize() method.
    }

    /**
     * Constructs the object.
     * @link https://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized The string representation of the object.
     * @return void
     */
    public function unserialize($serialized)
    {
        list( $this->id, $this->nombre, $this->categoria) =
            unserialize($serialized, ['allowed_classes' => false]);
    }


}

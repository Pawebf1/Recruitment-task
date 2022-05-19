<?php

namespace App\Entity;

use App\Repository\TransportRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransportRepository::class)]
class Transport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $transportFrom;

    #[ORM\Column(type: 'string', length: 255)]
    private $transportTo;

    #[ORM\Column(type: 'string', length: 255)]
    private $plane;

    #[ORM\Column(type: 'string', length: 255)]
    private $documents;

    #[ORM\Column(type: 'date')]
    private $date;

    #[ORM\OneToMany(mappedBy: 'transportID', targetEntity: Cargo::class)]
    private $cargos;

    public function __construct()
    {
        $this->cargos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTransportFrom(): ?string
    {
        return $this->transportFrom;
    }

    public function setTransportFrom(string $transportFrom): self
    {
        $this->transportFrom = $transportFrom;

        return $this;
    }

    public function getTransportTo(): ?string
    {
        return $this->transportTo;
    }

    public function setTransportTo(string $transportTo): self
    {
        $this->transportTo = $transportTo;

        return $this;
    }

    public function getPlane(): ?string
    {
        return $this->plane;
    }

    public function setPlane(string $plane): self
    {
        $this->plane = $plane;

        return $this;
    }

    public function getDocuments(): ?string
    {
        return $this->documents;
    }

    public function setDocuments(string $documents): self
    {
        $this->documents = $documents;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection<int, Cargo>
     */
    public function getCargos(): Collection
    {
        return $this->cargos;
    }

    public function addCargo(Cargo $cargo): self
    {
        if (!$this->cargos->contains($cargo)) {
            $this->cargos[] = $cargo;
            $cargo->setTransportID($this);
        }

        return $this;
    }

    public function removeCargo(Cargo $cargo): self
    {
        if ($this->cargos->removeElement($cargo)) {
            // set the owning side to null (unless already changed)
            if ($cargo->getTransportID() === $this) {
                $cargo->setTransportID(null);
            }
        }

        return $this;
    }
}

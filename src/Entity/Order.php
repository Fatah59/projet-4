<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 * @ORM\Table(name="oorder")
 */
class Order
{
    const FILLING = 0;
    const PAYING = 1;
    const PAYED = 2;

    public function __construct()
    {
        $this->setStatus(self::FILLING);
        $this->visitors = new ArrayCollection();
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $tourAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $fullday;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Visitor", mappedBy="order", orphanRemoval=true)
     */
    private $visitors;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTourAt(): ?\DateTimeInterface
    {
        return $this->tourAt;
    }

    public function setTourAt(\DateTimeInterface $tourAt): self
    {
        $this->tourAt = $tourAt;

        return $this;
    }

    public function getFullday(): ?bool
    {
        return $this->fullday;
    }

    public function setFullday(bool $fullday): self
    {
        $this->fullday = $fullday;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getNbVisitors(): ?int
    {
        return $this->nbVisitors;
    }

    public function setNbVisitors(int $nbVisitors): self
    {
        $this->nbVisitors = $nbVisitors;

        return $this;
    }

    /**
     * @return Collection|Visitor[]
     */
    public function getVisitors(): Collection
    {
        return $this->visitors;
    }

    public function addVisitor(Visitor $visitor): self
    {
        if (!$this->visitors->contains($visitor)) {
            $this->visitors[] = $visitor;
            $visitor->setOrder($this);
        }

        return $this;
    }

    public function removeVisitor(Visitor $visitor): self
    {
        if ($this->visitors->contains($visitor)) {
            $this->visitors->removeElement($visitor);
            // set the owning side to null (unless already changed)
            if ($visitor->getOrder() === $this) {
                $visitor->setOrder(null);
            }
        }

        return $this;
    }
}

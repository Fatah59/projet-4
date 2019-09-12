<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 */
class Order
{
    const FILLING = 0;
    const PAYING = 1;
    const PAYED = 2;

    public function __construct()
    {
        $this->setStatus(self::FILLING);
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
     * @ORM\Column(type="integer")
     */
    private $nbVisitors;

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
}

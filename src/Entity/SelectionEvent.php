<?php

namespace App\Entity;

use App\Repository\SelectionEventRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SelectionEventRepository::class)]
class SelectionEvent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column]
    private ?float $odd = null;

    #[ORM\ManyToOne(inversedBy: 'selectionEvents')]
    private ?Event $eventId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getOdd(): ?float
    {
        return $this->odd;
    }

    public function setOdd(float $odd): self
    {
        $this->odd = $odd;

        return $this;
    }

    public function getEventId(): ?Event
    {
        return $this->eventId;
    }

    public function setEventId(?Event $eventId): self
    {
        $this->eventId = $eventId;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\SelectionEventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\OneToMany(mappedBy: 'selectionEventId', targetEntity: Bet::class)]
    private Collection $bets;

    #[ORM\OneToOne(mappedBy: 'selectionEventId', cascade: ['persist', 'remove'])]
    private ?Event $event = null;

    public function __construct()
    {
        $this->bets = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Bet>
     */
    public function getBets(): Collection
    {
        return $this->bets;
    }

    public function addBet(Bet $bet): self
    {
        if (!$this->bets->contains($bet)) {
            $this->bets->add($bet);
            $bet->setSelectionEventId($this);
        }

        return $this;
    }

    public function removeBet(Bet $bet): self
    {
        if ($this->bets->removeElement($bet)) {
            // set the owning side to null (unless already changed)
            if ($bet->getSelectionEventId() === $this) {
                $bet->setSelectionEventId(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return "";
    }
    
    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        // unset the owning side of the relation if necessary
        if ($event === null && $this->event !== null) {
            $this->event->setSelectionEventId(null);
        }

        // set the owning side of the relation if necessary
        if ($event !== null && $event->getSelectionEventId() !== $this) {
            $event->setSelectionEventId($this);
        }

        $this->event = $event;

        return $this;
    }
}

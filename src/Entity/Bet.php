<?php

namespace App\Entity;

use App\Repository\BetRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BetRepository::class)]
class Bet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\Column]
    private ?float $odd = null;

    #[ORM\Column]
    private ?float $potentialGain = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'bets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userId = null;

    #[ORM\ManyToOne(targetEntity: SelectionEvent::class, inversedBy: 'bets')]
    private ?SelectionEvent $selectionEventId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

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

    public function getPotentialGain(): ?float
    {
        return $this->potentialGain;
    }

    public function setPotentialGain(float $potentialGain): self
    {
        $this->potentialGain = $potentialGain;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getSelectionEventId(): ?SelectionEvent
    {
        return $this->selectionEventId;
    }

    public function setSelectionEventId(?SelectionEvent $selectionEventId): self
    {
        $this->selectionEventId = $selectionEventId;

        return $this;
    }
}

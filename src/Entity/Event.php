<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column]
    private ?bool $isOpen = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userId = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'events')]
    private Collection $category;

    #[ORM\OneToMany(mappedBy: 'eventId', targetEntity: SelectionEvent::class)]
    private Collection $selectionEvents;

    #[ORM\OneToOne(inversedBy: 'event', cascade: ['persist', 'remove'])]
    private ?SelectionEvent $selectionEventId = null;

    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->selectionEvents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function isIsOpen(): ?bool
    {
        return $this->isOpen;
    }

    public function setIsOpen(bool $isOpen): self
    {
        $this->isOpen = $isOpen;

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

    /**
     * @return Collection<int, Category>
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->category->removeElement($category);

        return $this;
    }

    /**
     * @return Collection<int, SelectionEvent>
     */
    public function getSelectionEvents(): Collection
    {
        return $this->selectionEvents;
    }

    public function addSelectionEvent(SelectionEvent $selectionEvent): self
    {
        if (!$this->selectionEvents->contains($selectionEvent)) {
            $this->selectionEvents->add($selectionEvent);
            $selectionEvent->setEventId($this);
        }

        return $this;
    }

    public function removeSelectionEvent(SelectionEvent $selectionEvent): self
    {
        if ($this->selectionEvents->removeElement($selectionEvent)) {
            // set the owning side to null (unless already changed)
            if ($selectionEvent->getEventId() === $this) {
                $selectionEvent->setEventId(null);
            }
        }

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

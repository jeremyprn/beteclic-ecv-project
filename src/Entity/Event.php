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
    private ?bool $isopen = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userId = null;

    #[ORM\OneToMany(mappedBy: 'eventId', targetEntity: EventCategory::class, orphanRemoval: true)]
    private Collection $eventCategories;

    public function __construct()
    {
        $this->eventCategories = new ArrayCollection();
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

    public function isIsopen(): ?bool
    {
        return $this->isopen;
    }

    public function setIsopen(bool $isopen): self
    {
        $this->isopen = $isopen;

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
     * @return Collection<int, EventCategory>
     */
    public function getEventCategories(): Collection
    {
        return $this->eventCategories;
    }

    public function addEventCategory(EventCategory $eventCategory): self
    {
        if (!$this->eventCategories->contains($eventCategory)) {
            $this->eventCategories->add($eventCategory);
            $eventCategory->setEventId($this);
        }

        return $this;
    }

    public function removeEventCategory(EventCategory $eventCategory): self
    {
        if ($this->eventCategories->removeElement($eventCategory)) {
            // set the owning side to null (unless already changed)
            if ($eventCategory->getEventId() === $this) {
                $eventCategory->setEventId(null);
            }
        }

        return $this;
    }
}

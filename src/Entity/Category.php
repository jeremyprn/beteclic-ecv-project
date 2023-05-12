<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 160)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'categoryId', targetEntity: EventCategory::class)]
    private Collection $eventCategories;

    public function __construct()
    {
        $this->eventCategories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
            $eventCategory->setCategoryId($this);
        }

        return $this;
    }

    public function removeEventCategory(EventCategory $eventCategory): self
    {
        if ($this->eventCategories->removeElement($eventCategory)) {
            // set the owning side to null (unless already changed)
            if ($eventCategory->getCategoryId() === $this) {
                $eventCategory->setCategoryId(null);
            }
        }

        return $this;
    }
}

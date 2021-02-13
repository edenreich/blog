<?php

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=\App\Repository\NavigationRepository::class)
 * @ORM\Table(name="navigations")
 * @ORM\HasLifecycleCallbacks
 */
class Navigation
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidV4Generator::class)
     */
    private string $id;

    /**
     * @ORM\ManyToOne(targetEntity="Navigation", inversedBy="children")
     */
    private Navigation $parent;

    /**
     * @ORM\OneToMany(targetEntity="Navigation", mappedBy="parent", fetch="EAGER")
     */
    private Collection $children;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private string $url;

    /**
     * @ORM\Column(type="string", length=180)
     */
    private string $icon;

    /**
     * @ORM\Column(name="updated_at", type="datetimetz", nullable=true)
     * @Groups({"admin", "frontend"})
     */
    private ?DateTimeInterface $updatedAt = null;

    /**
     * @ORM\Column(name="created_at", type="datetimetz", nullable=false)
     * @Groups({"admin", "frontend"})
     */
    private DateTimeInterface $createdAt;

    /**
     * Initialize properties.
     */
    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->children = new ArrayCollection();
    }

    /**
     * Gets triggered every time on update.
     *
     * @ORM\PreUpdate
     */
    public function onPreUpdate(): void
    {
        $this->updatedAt = new DateTime();
    }

    public function getId(): ?string
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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|Navigation[]
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(Navigation $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(Navigation $child): self
    {
        if ($this->children->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }
}

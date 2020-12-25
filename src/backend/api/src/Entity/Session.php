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
 * Session.
 *
 * @ORM\Table(name="sessions", uniqueConstraints={@ORM\UniqueConstraint(name="sessions_ip_address_unique", columns={"ip_address"}), @ORM\UniqueConstraint(name="sessions_id_unique", columns={"id"})})
 * @ORM\Entity(repositoryClass="App\Repository\SessionRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Session
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidV4Generator::class)
     * @Groups({"admin", "frontend"})
     */
    private ?string $id = null;

    /**
     * @ORM\Column(name="ip_address", type="string", length=255, nullable=false)
     * @Groups({"admin", "frontend"})
     */
    private string $ipAddress = '';

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     * @Groups({"admin", "frontend"})
     */
    private ?DateTimeInterface $updatedAt = null;

    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     * @Groups({"admin", "frontend"})
     */
    private DateTimeInterface $createdAt;

    /**
     * @var Collection|Reaction[]
     *
     * @ORM\OneToMany(targetEntity="Reaction", mappedBy="session", cascade={"persist", "remove"})
     * @Groups({"admin", "frontend"})
     */
    private Collection $reactions;

    /**
     * @ORM\OneToOne(targetEntity="Notification", mappedBy="session", cascade={"persist", "remove"})
     * @Groups({"admin", "frontend"})
     */
    private ?Notification $notification = null;

    /**
     * Initialize properties.
     */
    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->reactions = new ArrayCollection();
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

    /**
     * Get the value of id.
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Get the value of ipAddress.
     */
    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    /**
     * Set the value of ipAddress.
     */
    public function setIpAddress(string $ipAddress): self
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    /**
     * Get the value of updatedAt.
     */
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * Set the value of updatedAt.
     */
    public function setUpdatedAt(?DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get the value of createdAt.
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt.
     */
    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Add a reaction.
     */
    public function addReaction(Reaction $reaction): self
    {
        if (!$this->reactions->contains($reaction)) {
            $this->reactions[] = $reaction;
            $reaction->setSession($this);
        }

        return $this;
    }

    /**
     * Remove a reaction.
     */
    public function removeReaction(Reaction $reaction): self
    {
        if ($this->reactions->removeElement($reaction)) {
            // set the owning side to null (unless already changed)
            if ($reaction->getSession() === $this) {
                $reaction->setSession(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Reaction[]
     */
    public function getReactions(): Collection
    {
        return $this->reactions;
    }

    /**
     * Get the value of Notification.
     */
    public function getNotification(): ?Notification
    {
        return $this->notification;
    }

    /**
     * Set the value of notification.
     */
    public function setNotification(?Notification $notification): self
    {
        $this->notification = $notification;
        $notification->setSession($this);

        return $this;
    }
}

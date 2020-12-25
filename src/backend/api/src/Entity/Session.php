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
     * @var Reaction[]
     *
     * @ORM\OneToMany(targetEntity="Reaction", mappedBy="session", cascade={"persist", "remove"})
     */
    private Collection $reactions;

    /**
     * Initialize properties.
     */
    public function __construct()
    {
        $this->reactions = new ArrayCollection();
        $this->createdAt = new DateTime();
    }

    /**
     * Gets triggered every time on update.
     *
     * @ORM\PreUpdate
     */
    public function onPreUpdate(): void
    {
        $this->setUpdatedAt(new DateTime('now'));
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
     * Get the value of reactions.
     *
     * @return Reaction[]
     */
    public function getReactions(): Collection
    {
        return $this->reactions;
    }

    /**
     * Set the value of reactions.
     *
     * @param Reaction[] $reactions
     */
    public function setReactions(array $reactions): self
    {
        $this->reactions = $reactions;

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
}

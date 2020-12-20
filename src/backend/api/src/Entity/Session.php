<?php

namespace App\Entity;

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
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidV4Generator::class)
     * @Groups({"admin", "frontend"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_address", type="string", length=255, nullable=false)
     * @Groups({"admin", "frontend"})
     */
    private $ipAddress;

    /**
     * @var \DateTimeInterface|null
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     * @Groups({"admin", "frontend"})
     */
    private $createdAt;

    /**
     * @var \DateTimeInterface|null
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     * @Groups({"admin", "frontend"})
     */
    private $updatedAt;

    /**
     * @var Like[]
     *
     * @ORM\OneToMany(targetEntity="Like", mappedBy="session", cascade={"persist", "remove"})
     */
    private $likes;

    /**
     * Gets triggered only on insert.
     *
     * @ORM\PrePersist
     */
    public function onPrePersist(): void
    {
        $this->setCreatedAt(new \DateTime('now'));
    }

    /**
     * Gets triggered every time on update.
     *
     * @ORM\PreUpdate
     */
    public function onPreUpdate(): void
    {
        $this->setUpdatedAt(new \DateTime('now'));
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
     * Get the value of likes.
     *
     * @return Like[]
     */
    public function getLikes(): array
    {
        return $this->likes;
    }

    /**
     * Set the value of likes.
     *
     * @param Like[] $likes
     */
    public function setLikes(array $likes): self
    {
        $this->likes = $likes;

        return $this;
    }

    /**
     * Get the value of createdAt.
     *
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt.
     *
     * @param \DateTimeInterface|null $createdAt
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get the value of updatedAt.
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * Set the value of updatedAt.
     */
    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}

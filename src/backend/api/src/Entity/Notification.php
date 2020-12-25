<?php

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;

/**
 * Notification.
 *
 * @ORM\Table(name="notifications", uniqueConstraints={@ORM\UniqueConstraint(name="notifications_session_unique", columns={"session_id"}), @ORM\UniqueConstraint(name="notifications_email_unique", columns={"email"}), @ORM\UniqueConstraint(name="notifications_id_unique", columns={"id"})})
 * @ORM\Entity(repositoryClass="App\Repository\NotificationRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Notification
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
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     * @Groups({"admin", "frontend"})
     */
    private string $email = '';

    /**
     * @ORM\Column(name="is_enabled", type="boolean", nullable=false)
     * @Groups({"admin", "frontend"})
     */
    private bool $isEnabled;

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
     * @ORM\OneToOne(targetEntity="Session")
     * @ORM\JoinColumn(name="session_id", referencedColumnName="id")
     * @Groups({"admin", "frontend"})
     * @Ignore()
     */
    private ?Session $session = null;

    /**
     * Initialize properties.
     */
    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    /**
     * Gets triggered every time on update.
     *
     * @ORM\PreUpdate
     */
    public function onPreUpdate(): void
    {
        $this->updatedAt= new DateTime();
    }

    /**
     * Get the value of id.
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Get the value of email.
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set the value of email.
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of isEnabled.
     */
    public function getIsEnabled(): bool
    {
        return $this->isEnabled;
    }

    /**
     * Set the value of isEnabled.
     */
    public function setIsEnabled(bool $isEnabled): self
    {
        $this->isEnabled = $isEnabled;

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
     * Get the value of session.
     */
    public function getSession(): Session
    {
        return $this->session;
    }

    /**
     * Set the value of session.
     */
    public function setSession(Session $session): self
    {
        $this->session = $session;

        return $this;
    }
}

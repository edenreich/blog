<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;

/**
 * Notification.
 *
 * @ORM\Table(name="notifications", uniqueConstraints={@ORM\UniqueConstraint(name="notifications_session_unique", columns={"session_id"}), @ORM\UniqueConstraint(name="notifications_email_unique", columns={"email"}), @ORM\UniqueConstraint(name="notifications_id_unique", columns={"id"})})
 * @ORM\Entity
 */
class Notification
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidV4Generator::class)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_enabled", type="boolean", nullable=false)
     */
    private $isEnabled;

    /**
     * @var \DateTimeInterface|null
     *
     * @ORM\Column(name="created_at", type="datetimetz", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTimeInterface|null
     *
     * @ORM\Column(name="updated_at", type="datetimetz", nullable=true)
     */
    private $updatedAt;

    /**
     * @var Session
     *
     * @ORM\OneToOne(targetEntity="Session")
     */
    private $session;

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
    public function setCreatedAt($createdAt): self
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
     *
     * @param \DateTimeInterface|null $updatedAt
     */
    public function setUpdatedAt($updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}

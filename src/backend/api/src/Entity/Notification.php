<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;

/**
 * Notification
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
     * @var int|null
     *
     * @ORM\Column(name="created_by", type="integer", nullable=true)
     */
    private $createdBy;

    /**
     * @var int|null
     *
     * @ORM\Column(name="updated_by", type="integer", nullable=true)
     */
    private $updatedBy;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="created_at", type="datetimetz", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated_at", type="datetimetz", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $updatedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var Session
     *
     * @ORM\OneToOne(targetEntity="Session")
     */
    private $session;

    /**
     * Get the value of id
     *
     * @return string|null
     */ 
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Get the value of email
     *
     * @return string
     */ 
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @param string $email
     *
     * @return self
     */ 
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of isEnabled
     *
     * @return bool
     */ 
    public function getIsEnabled(): bool
    {
        return $this->isEnabled;
    }

    /**
     * Set the value of isEnabled
     *
     * @param bool $isEnabled
     *
     * @return self
     */ 
    public function setIsEnabled(bool $isEnabled): self
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    /**
     * Get the value of session
     *
     * @return Session
     */ 
    public function getSession(): Session
    {
        return $this->session;
    }

    /**
     * Set the value of session
     *
     * @param Session $session
     *
     * @return self
     */ 
    public function setSession(Session $session): self
    {
        $this->session = $session;

        return $this;
    }
}

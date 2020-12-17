<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;

/**
 * Session
 *
 * @ORM\Table(name="sessions", uniqueConstraints={@ORM\UniqueConstraint(name="sessions_ip_address_unique", columns={"ip_address"}), @ORM\UniqueConstraint(name="sessions_id_unique", columns={"id"})})
 * @ORM\Entity
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
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_address", type="string", length=255, nullable=false)
     */
    private $ipAddress;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $updatedAt = 'CURRENT_TIMESTAMP';

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
     * @var Like[]
     *
     * @ORM\OneToMany(targetEntity="Like", mappedBy="session")
     */
    private $likes;

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
     * Get the value of ipAddress
     *
     * @return string
     */ 
    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    /**
     * Set the value of ipAddress
     *
     * @param string $ipAddress
     *
     * @return self
     */ 
    public function setIpAddress(string $ipAddress): self
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    /**
     * Get the value of likes
     *
     * @return Like[]
     */ 
    public function getLikes(): array
    {
        return $this->likes;
    }

    /**
     * Set the value of likes
     *
     * @param Like[] $likes
     *
     * @return self
     */ 
    public function setLikes(array $likes): self
    {
        $this->likes = $likes;

        return $this;
    }
}

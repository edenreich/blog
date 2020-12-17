<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;

/**
 * Like
 *
 * @ORM\Table(name="likes", uniqueConstraints={@ORM\UniqueConstraint(name="likes_id_unique", columns={"id"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Like
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
     * @ORM\Column(name="reaction_type", type="string", columnDefinition="reaction"), nullable=false)
     */
    private $reactionType;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="created_at", type="datetimetz", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated_at", type="datetimetz", nullable=true)
     */
    private $updatedAt;

    /**
     * @var Article
     *
     * @ORM\ManyToOne(targetEntity="Article")
     */
    private $article;

    /**
     * @var Session
     *
     * @ORM\ManyToOne(targetEntity="Session")
     */
    private $session;

    /**
     * Initialize properties.
     * 
     * @param array $attributes
     */
    public function __construct(array $properties)
    {
        if (isset($properties['reactionType'])) {
            $this->setReactionType($properties['reactionType']);
        }
        if (isset($properties['article'])) {
            $this->setArticle($properties['article']);
        }
        if (isset($properties['session'])) {
            $this->setSession($properties['session']);
        }
    }

    /**
     * Gets triggered only on insert

     * @ORM\PrePersist
     * 
     * @return void
     */
    public function onPrePersist(): void
    {
        $this->setCreatedAt(new \DateTime("now"));
    }

    /**
     * Gets triggered every time on update

     * @ORM\PreUpdate
     * 
     * @return void
     */
    public function onPreUpdate(): void
    {
        $this->setUpdatedAt(new \DateTime("now"));
    }

    /**
     * Get the value of id
     *
     * @return string
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of reactionType
     *
     * @return string
     */ 
    public function getReactionType(): string
    {
        return $this->reactionType;
    }

    /**
     * Set the value of reactionType
     *
     * @param string $reactionType
     *
     * @return self
     */ 
    public function setReactionType($reactionType): self
    {
        $this->reactionType = $reactionType;

        return $this;
    }

    /**
     * Get the value of article
     *
     * @return Article
     */ 
    public function getArticle(): Article
    {
        return $this->article;
    }

    /**
     * Set the value of article
     *
     * @param Article $article
     *
     * @return self
     */ 
    public function setArticle(Article $article): self
    {
        $this->article = $article;

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

    /**
     * Get the value of createdAt
     *
     * @return \DateTimeInterface|null
     */ 
    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt
     *
     * @param \DateTimeInterface|null $createdAt
     *
     * @return self
     */ 
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get the value of updatedAt
     *
     * @return \DateTimeInterface|null
     */ 
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * Set the value of updatedAt
     *
     * @param \DateTimeInterface|null $updatedAt
     *
     * @return self
     */ 
    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}

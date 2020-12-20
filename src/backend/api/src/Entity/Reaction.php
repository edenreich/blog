<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Reaction.
 *
 * @ORM\Table(name="reactions", uniqueConstraints={@ORM\UniqueConstraint(name="reactions_articles_sessions_id_unique", columns={"id", "article_id", "session_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\ReactionRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Reaction
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
     * @ORM\Column(name="type", type="string", length=10), nullable=false)
     * @Groups({"admin", "frontend"})
     */
    private $type;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="created_at", type="datetimetz", nullable=true)
     * @Groups({"admin", "frontend"})
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated_at", type="datetimetz", nullable=true)
     * @Groups({"admin", "frontend"})
     */
    private $updatedAt;

    /**
     * @var Article
     *
     * @ORM\ManyToOne(targetEntity="Article", inversedBy="reactions", fetch="LAZY")
     */
    private $article;

    /**
     * @var Session
     *
     * @ORM\ManyToOne(targetEntity="Session", inversedBy="reactions", fetch="LAZY")
     */
    private $session;

    /**
     * Initialize properties.
     *
     * @param array $attributes
     */
    public function __construct(array $properties)
    {
        if (isset($properties['type'])) {
            $this->validateType($properties['type']);
            $this->setType($properties['type']);
        }
        if (isset($properties['article'])) {
            $this->setArticle($properties['article']);
        }
        if (isset($properties['session'])) {
            $this->setSession($properties['session']);
        }
    }

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
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of type.
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Set the value of type.
     */
    public function setType(string $type): self
    {
        $this->validateType($type);
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of article.
     */
    public function getArticle(): Article
    {
        return $this->article;
    }

    /**
     * Set the value of article.
     */
    public function setArticle(Article $article): self
    {
        $this->article = $article;

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

    /**
     * Validation for reaction type.
     */
    private function validateType(string $type): void
    {
        $availableReactionTypes = ['like' => 'like', 'love' => 'love', 'dislike' => 'dislike'];
        if (!isset($availableReactionTypes[$type])) {
            throw new \LogicException(sprintf('Attempting to set an invalid type %s. Use one of the following %s', $type, implode(', ', $availableReactionTypes)));
        }
    }
}

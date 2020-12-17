<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;

/**
 * Like
 *
 * @ORM\Table(name="like", uniqueConstraints={@ORM\UniqueConstraint(name="likes_id_unique", columns={"id"})})
 * @ORM\Entity
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
     * @ORM\Column(name="reaction_type", type="string", columnDefinition="ENUM('like', 'love', 'dislike')"), nullable=false)
     */
    private $reactionType;

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
}

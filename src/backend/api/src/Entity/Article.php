<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Article.
 *
 * @ORM\Table(name="articles", uniqueConstraints={@ORM\UniqueConstraint(name="articles_id_unique", columns={"id"})})
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Article
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
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     * @Groups({"admin", "frontend"})
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, nullable=false)
     * @Groups({"admin", "frontend"})
     */
    private $slug;

    /**
     * @var string|null
     *
     * @ORM\Column(name="meta_keywords", type="string", length=255, nullable=true)
     * @Groups({"admin", "frontend"})
     */
    private $metaKeywords;

    /**
     * @var string|null
     *
     * @ORM\Column(name="meta_description", type="string", length=255, nullable=true)
     * @Groups({"admin", "frontend"})
     */
    private $metaDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=false)
     * @Groups({"admin", "frontend"})
     */
    private $content;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="published_at", type="datetimetz", nullable=true)
     * @Groups({"admin", "frontend"})
     */
    private $publishedAt;

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
     * @var ArrayCollection<Reaction[]>
     *
     * @ORM\OneToMany(targetEntity="Reaction", mappedBy="article", fetch="EAGER")
     * @Groups({"admin", "frontend"})
     */
    private $reactions;

    /**
     * Initialize properties.
     */
    public function __construct()
    {
        $this->reactions = new ArrayCollection();
    }

    /**
     * Gets triggered only on insert.
     *
     * @ORM\PrePersist
     */
    public function onPrePersist(): void
    {
        if (is_null($this->createdAt)) {
            $this->setCreatedAt(new \DateTime('now'));
        }
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
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Get the value of title.
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Set the value of title.
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of slug.
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * Set the value of slug.
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get the value of metaKeywords.
     */
    public function getMetaKeywords(): ?string
    {
        return $this->metaKeywords;
    }

    /**
     * Set the value of metaKeywords.
     *
     * @param string|null $metaKeywords
     */
    public function setMetaKeywords($metaKeywords): self
    {
        $this->metaKeywords = $metaKeywords;

        return $this;
    }

    /**
     * Get the value of metaDescription.
     */
    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    /**
     * Set the value of metaDescription.
     *
     * @param string|null $metaDescription
     */
    public function setMetaDescription($metaDescription): self
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * Get the value of content.
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Set the value of content.
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get the value of publishedAt.
     */
    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    /**
     * Set the value of publishedAt.
     */
    public function setPublishedAt(\DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

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
     * Get the value of reactions.
     *
     * @return ArrayCollection<Reaction[]>
     */
    public function getReactions(): Collection
    {
        return $this->reactions;
    }

    /**
     * Set the value of reactions.
     *
     * @param Reaction $reaction
     */
    public function addReaction(Reaction $reaction): self
    {
        $this->reactions->add($reaction);

        return $this;
    }
}

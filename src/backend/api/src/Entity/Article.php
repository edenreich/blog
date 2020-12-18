<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Article
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
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
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
     * @var ArrayCollection<Like[]>
     *
     * @ORM\OneToMany(targetEntity="Like", mappedBy="article", fetch="EAGER")
     * @Groups({"admin", "frontend"})
     */
    private $likes;

    /**
     * Initialize properties.
     */
    public function __construct()
    {
        // $this->likes = new ArrayCollection();
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
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Get the value of title
     *
     * @return string
     */ 
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @param string  $title
     *
     * @return self
     */ 
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of slug
     *
     * @return string
     */ 
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * Set the value of slug
     *
     * @param string $slug
     *
     * @return self
     */ 
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get the value of metaKeywords
     *
     * @return string|null
     */ 
    public function getMetaKeywords(): ?string
    {
        return $this->metaKeywords;
    }

    /**
     * Set the value of metaKeywords
     *
     * @param string|null $metaKeywords
     *
     * @return self
     */ 
    public function setMetaKeywords($metaKeywords): self
    {
        $this->metaKeywords = $metaKeywords;

        return $this;
    }

    /**
     * Get the value of metaDescription
     *
     * @return string|null
     */ 
    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    /**
     * Set the value of metaDescription
     *
     * @param string|null $metaDescription
     *
     * @return self
     */ 
    public function setMetaDescription($metaDescription): self
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * Get the value of content
     *
     * @return string
     */ 
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Set the value of content
     *
     * @param string $content
     *
     * @return self
     */ 
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get the value of publishedAt
     *
     * @return \DateTimeInterface|null
     */ 
    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    /**
     * Set the value of publishedAt
     *
     * @param \DateTimeInterface $publishedAt
     *
     * @return self
     */ 
    public function setPublishedAt(\DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

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

    /**
     * Get the value of likes
     *
     * @return ArrayCollection<Like[]>
     */ 
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    /**
     * Set the value of likes
     *
     * @param Like $likes
     *
     * @return self
     */ 
    public function addLike($like): self
    {
        $this->likes->add($like);

        return $this;
    }
}

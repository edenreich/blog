<?php

namespace App\Dto;

class Article
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string|null
     */
    private $metaKeywords;

    /**
     * @var string|null
     */
    private $metaDescription;

    /**
     * @var string
     */
    private $content;

    /**
     * @var \DateTime|null
     */
    private $publishedAt;

    /**
     * @var \DateTimeInterface|null
     */
    private $createdAt;

    /**
     * @var \DateTimeInterface|null
     */
    private $updatedAt;

    /**
     * Get the value of id.
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
     */
    public function setMetaKeywords(?string $metaKeywords): self
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
     */
    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt.
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

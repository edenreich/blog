<?php

namespace App\Repository;

use App\Entity\Article;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method bool         delete(int $id)
 * @method Article      store(array $attributes)
 * @method Article      update(string $id, array $attributes)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * @return Article[] Returns an array of Article objects
     */
    public function findAll(): array
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.createdAt', 'DESC')
            ->where('a.deletedAt IS NULL')
            ->getQuery()
            ->getResult();
    }

    /**
     * Delete an article by given id.
     */
    public function delete(string $id): bool
    {
        $em = $this->getEntityManager();

        /** @var Article */
        $article = $em->find(Article::class, $id);

        if (!$article) {
            return false;
        }

        $article->setDeletedAt(new DateTime());
        $em->flush();

        return true;
    }

    /**
     * Create a new article.
     *
     * @throws ORMInvalidArgumentException
     * @throws ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function store(array $attributes): Article
    {
        $article = new Article($attributes);
        $em = $this->getEntityManager();

        $em->persist($article);
        $em->flush();

        return $article;
    }

    /**
     * Create a new article.
     *
     * @throws ORMInvalidArgumentException
     * @throws ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(string $id, array $attributes): Article
    {
        $em = $this->getEntityManager();
        /** @var Article */
        $article = $em->find(Article::class, $id);

        if (!$article) {
            throw new Exception(sprintf('Article not found by %s', $id));
        }

        if (isset($attributes['title'])) {
            $article->setTitle($attributes['title']);
        }
        if (isset($attributes['slug'])) {
            $article->setSlug($attributes['slug']);
        }
        if (isset($attributes['meta_keywords'])) {
            $article->setMetaKeywords($attributes['meta_keywords']);
        }
        if (isset($attributes['meta_description'])) {
            $article->setMetaDescription($attributes['meta_description']);
        }
        if (isset($attributes['content'])) {
            $article->setContent($attributes['content']);
        }

        $em->flush();

        return $article;
    }
}

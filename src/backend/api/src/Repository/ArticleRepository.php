<?php

namespace App\Repository;

use App\Entity\Article;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method bool         delete(int $id)
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
}

<?php

namespace App\Repository;

use App\Entity\Navigation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Navigation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Navigation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Navigation[]    findAll()
 * @method Navigation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Navigation[]    findAllCategories()
 */
class NavigationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Navigation::class);
    }

    /**
     * @return Navigation[]|array
     */
    public function findAllCategories(): array
    {
        $categories = $this->createQueryBuilder('c')
            ->where('c.parent IS NULL')
            ->getQuery()
            ->getResult();

        return $categories;
    }
}
<?php

namespace App\Repository;

use App\Entity\Notification;
use App\Entity\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Notification|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notification|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notification[]    findAll()
 * @method Notification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Notification      store(array $body)
 */
class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    /**
     * Store a notification, default to enabled.
     */
    public function store(array $body): Notification
    {
        try {
            /** 
             * @var Notification|null
             * @throws NoResultException 
             **/
            $notification = $this->getEntityManager()
                ->createQueryBuilder('n')
                ->select('n')
                ->from(Notification::class, 'n')
                ->setParameter('session', $body['session'])
                ->setParameter('email', $body['email'])
                ->where('n.session = :session')
                ->orWhere('n.email = :email')
                ->getQuery()
                ->getSingleResult();
            $notification->setIsEnabled(true);
        } catch (NoResultException $exception) {
            $session = $this->getEntityManager()->getRepository(Session::class)->findOneBy(['id' => $body['session']]);
            $notification = new Notification;
            $notification->setEmail($body['email']);
            $notification->setSession($session);
            $notification->setIsEnabled(true);
            $this->getEntityManager()->persist($notification);
        } finally {
            $this->getEntityManager()->flush();
            return $notification;
        }
    }

    /**
     * Update a notification.
     */
    public function update(string $id, array $body): ?Notification
    {
        $session = $this->getEntityManager()->getRepository(Session::class)->findOneBy(['id' => $body['session']]);

        try {
            /** 
             * @var Notification|null
             * @throws NoResultException 
             **/
            $notification = $this->getEntityManager()
                ->createQueryBuilder('n')
                ->select('n')
                ->from(Notification::class, 'n')
                ->setParameter('id', $id)
                ->setParameter('session', $body['session'] ?? 'none')
                ->setParameter('email', $body['email'] ?? 'none')
                ->where('n.id = :id')
                ->orWhere('n.session = :session')
                ->orWhere('n.email = :email')
                ->getQuery()
                ->getSingleResult();
            $notification->setIsEnabled($body['is_enabled']);
            $notification->setSession($session);
            $this->getEntityManager()->flush();
            return $notification;
        } catch (NoResultException $exception) {
            return null;
        }
    }
}

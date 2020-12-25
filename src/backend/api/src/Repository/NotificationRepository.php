<?php

namespace App\Repository;

use App\Entity\Notification;
use App\Entity\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
     * Store or update a notification.
     */
    public function store(array $body): Notification
    {
        /** @var Session */
        $session = $this->getEntityManager()->getRepository(Session::class)->findOneBy(['id' => $body['session']]);

        if (!$session) {
            throw new \Exception(sprintf('Session id %s does not exist', $body['session']));
        }

        $notification = $this->findOneBy(['email' => $body['email']]);

        if (!$notification) {
            $notification = new Notification();
            $notification->setEmail($body['email']);
            $notification->setSession($session);
            $notification->setIsEnabled($body['is_enabled']);

            $this->getEntityManager()->persist($notification);
        } else {
            $notification->setSession($session);
            $notification->setIsEnabled($body['is_enabled']);
        }

        $this->getEntityManager()->flush();

        return $notification;
    }
}

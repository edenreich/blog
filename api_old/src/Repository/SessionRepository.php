<?php

namespace App\Repository;

use App\Entity\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Session|null find($id, $lockMode = null, $lockVersion = null)
 * @method Session|null findOneBy(array $criteria, array $orderBy = null)
 * @method Session[]    findAll()
 * @method Session[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Session      store(string $ipAddress)
 */
class SessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Session::class);
    }

    /**
     * Store a session to the database
     * by given ip address.
     */
    public function store(string $ipAddress): Session
    {
        $session = $this->findOneBy(['ipAddress' => $ipAddress]);

        if (!$session) {
            $session = new Session();
            $session->setIpAddress($ipAddress);

            $this->_em->persist($session);
            $this->_em->flush();
        }

        return $session;
    }
}

<?php

namespace App\Repository;

use App\Entity\Reaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reaction[]    findAll()
 * @method Reaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reaction::class);
    }

    /**
     * Store a reaction.
     */
    public function store(array $data): Reaction
    {
        $em = $this->getEntityManager();
        $article = $em->getRepository(\App\Entity\Article::class)->findOneBy(['id' => $data['article']]);
        $session = $em->getRepository(\App\Entity\Session::class)->findOneBy(['id' => $data['session']]);

        if (!$article || !$session) {
            throw new \Exception(sprintf('Article id %s or session id %s does not exist', $data['article'], $data['session']));
        }

        $reaction = $this->findOneBy(['session' => $session, 'article' => $article]);
        $data['article'] = $article;
        $data['session'] = $session;

        if ($reaction) {
            $reaction->setType($data['type']);
        } else {
            $reaction = new Reaction($data);
            $em->persist($reaction);
        }

        $em->flush();

        return $reaction;
    }
}

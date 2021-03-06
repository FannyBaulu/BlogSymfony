<?php

namespace App\Application\Repository;

use App\Application\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @return Paginator
     */
    public function getPaginatedPosts(int $page, int $limit): Paginator {
        return new Paginator (
        $this->createQueryBuilder("p")
        // ->addSelect("c")
        // ->join("p.comments","c")
        ->setMaxResults($limit)
        ->setFirstResult(($page-1)*$limit));
    }

    
}

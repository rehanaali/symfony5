<?php

namespace App\Repository;

use App\Entity\Todo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Todo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Todo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Todo[]    findAll()
 * @method Todo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TodoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Todo::class);
    }



    public function delete_all_completed(){


        if($this->findBy(["completed"=>'1'])){
            $queryBuilder = $this->getEntityManager()->createQueryBuilder()
            ->delete('App\Entity\Todo', 't')
            ->where('t.completed = :completed')
            ->setParameter('completed', 1)
            ->getQuery();
            return $queryBuilder->getResult();
        }

    }
}

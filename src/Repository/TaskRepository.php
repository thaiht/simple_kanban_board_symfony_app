<?php

namespace App\Repository;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function searchTasks(?User $user, $keyword): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.user = :user')
            ->setParameter('user', $user)
            ->andWhere('LOWER(t.title) LIKE :keyword OR LOWER(t.description) LIKE :keyword')
            ->setParameter('keyword', '%'.mb_strtolower($keyword).'%')
            ->getQuery()
            ->getResult();
    }
}

<?php


namespace App\Service;


use App\Entity\Employee;
use App\Entity\Project;
use Doctrine\Persistence\ManagerRegistry;

class MaxProfitService implements ProfitInterface
{
    private ManagerRegistry $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function getEmployeeByIncome(): ?Employee {
        $qb = $this->registry->getRepository(Employee::class)->createQueryBuilder('e')
            ->select()
            ->orderBy('e.salary', 'DESC')
            ->setMaxResults(1);

        $employee = $qb->getQuery()->getOneOrNullResult();

        return $employee;
    }

    public function getProjectByVolume(): ?Project {
        $qb = $this->registry->getRepository(Project::class)->createQueryBuilder('p')
            ->select()
            ->orderBy('p.price', 'DESC')
            ->setMaxResults(1);

        $project = $qb->getQuery()->getOneOrNullResult();

        return $project;
    }
}

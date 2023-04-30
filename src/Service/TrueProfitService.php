<?php


namespace App\Service;


use App\Entity\Employee;
use App\Entity\Project;
use Doctrine\Persistence\ManagerRegistry;

class TrueProfitService implements ProfitInterface
{
    private ManagerRegistry $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function getEmployeeByIncome(): ?Employee {
        $employeeNumber = sizeof($this->registry->getRepository(Employee::class)->findAll());

        // es wird eh immer aufgerundet
        $medianNumber = round($employeeNumber /= 2);

        $qb = $this->registry->getRepository(Employee::class)->createQueryBuilder('e')
            ->select()
            ->orderBy('e.salary', 'ASC')
            ->setMaxResults($medianNumber);

        $employee = $qb->getQuery()->getResult()[$medianNumber - 1];

        return $employee;
    }

    public function getProjectByVolume(): ?Project {
        $projects = $this->registry->getRepository(Project::class)->findAll();

        $highestProject = null;
        $highestProfit = null;

        foreach ($projects as $project) {
            $employees = $project->getEmployees();

            $profit = $project->getPrice();

            foreach ($employees as $employee) {
                $profit -= $employee->getSalary();
            }

            if (!is_null($highestProject)) {
                if ($profit > $highestProfit) {
                    $highestProfit = $profit;
                    $highestProject = $project;
                }
            } else {
                $highestProfit = $profit;
                $highestProject = $project;
            }
        }

        return $highestProject;
    }
}

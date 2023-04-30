<?php

namespace App\Service;

use App\Entity\Employee;
use App\Entity\Project;

interface ProfitInterface {
    public function getEmployeeByIncome(): ?Employee;

    public function getProjectByVolume(): ?Project;
}

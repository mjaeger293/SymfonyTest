<?php

namespace App\Controller;

use App\Entity\Employee;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeController extends AbstractController
{
    private ManagerRegistry $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    #[Route('/employee/add', name: 'add_employee')]
    public function addEmployee(Request $request): Response
    {
        $employee = new Employee();

        $form = $this->createFormBuilder($employee)
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('salary', NumberType::class)
            ->add('email', EmailType::class)
            ->add('project', null)
            ->add('save', SubmitType::class, ['label' => 'Speichern'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $employee = $form->getData();

            $this->registry->getManager()->persist($employee);
            $this->registry->getManager()->flush();

            return $this->redirectToRoute('show_employees', ['id' => $employee->getProject()->getId()]);
        }

        return $this->renderForm('employee/create.html.twig', [
            'controller_name' => 'EmployeeController',
            'form' => $form,
        ]);
    }
}

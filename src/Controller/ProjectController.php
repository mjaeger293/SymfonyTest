<?php

namespace App\Controller;

use App\Entity\Project;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    private ManagerRegistry $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    #[Route(['/'], name: 'list_projects')]
    public function list_projects(): Response
    {
        $projects = $this->registry->getRepository(Project::class)->findAll();

        return $this->render('project/list.html.twig', [
            'controller_name' => 'ProjectController',
            'projects' => $projects,
        ]);
    }

    #[Route('/project/add', name: 'add_project')]
    public function addProject(Request $request): Response
    {
        $project = new Project();

        $form = $this->createFormBuilder($project)
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('price', NumberType::class)
            ->add('save', SubmitType::class, ['label' => 'Speichern'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project = $form->getData();

            $this->registry->getManager()->persist($project);
            $this->registry->getManager()->flush();

            return $this->redirectToRoute('show_employees', ['id' => $project->getId()]);
        }

        return $this->renderForm('project/create.html.twig', [
            'controller_name' => 'ProjectController',
            'form' => $form,
        ]);
    }

    #[Route('/project/{id}', name: 'show_employees', requirements: ['id' => '\d+'])]
    public function showEmployees(int $id): Response
    {
        $project = $this->registry->getRepository(Project::class)->find($id);


        return $this->render('project/index.html.twig', [
            'controller_name' => 'ProjectController',
            'project' => $project,
        ]);
    }
}

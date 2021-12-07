<?php

namespace App\Controller;

use App\Entity\People;
use App\Form\PeopleType;
use App\Repository\PeopleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PeopleController extends AbstractController
{
    #[Route('/people/create', name: 'people_create')]
    public function create(
        Request $request,
        EntityManagerInterface $manager
    ): Response {

        $people = new People;

        $form = $this->createForm(PeopleType::class, $people);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($people);
            $manager->flush();

            return $this->redirectToRoute('people_list');
        }

        return $this->render('people/create.html.twig', [
            'formView' => $form->createView()
        ]);
    }

    #[Route('/people/list', name: 'people_list')]
    public function show(PeopleRepository $peopleRepository): Response
    {
        $peoplesList = $peopleRepository->findAll();

        return $this->render('people/show.html.twig', [
            'peoplesList' => $peoplesList,
        ]);
    }
}

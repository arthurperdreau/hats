<?php

namespace App\Controller;

use App\Entity\Hat;
use App\Form\HatType;
use App\Repository\HatRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HatController extends AbstractController
{
    #[Route('/hats', name: 'hats')]
    public function index(HatRepository $hatRepository): Response
    {
        return $this->render('hat/index.html.twig', [
            "hats" => $hatRepository->findAll(),
        ]);
    }

    #[Route('/hat/{id}', name: 'show_hat', priority: -1)]
    public function show(Hat $hat): Response
    {
        return $this->render('hat/show.html.twig', [
            "hat" => $hat,
        ]);
    }

    #[Route('/hat/create', name: 'create_hat')]
    public function create(Request $request, EntityManagerInterface $manager):Response
    {
        $hat = new Hat();
        $form = $this->createForm(HatType::class, $hat);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($hat);
            $manager->flush();
            return $this->redirectToRoute('show_hat', ['id' => $hat->getId()]);
        }

        return $this->render('hat/create.html.twig', [
            "form" => $form->createView(),
        ]);

    }

    #[Route('/hat/delete/{id}', name: 'delete_hat')]
    public function delete(Hat $hat, EntityManagerInterface $manager):Response
    {
        if($hat) {
            $manager->remove($hat);
            $manager->flush();
        }
        return $this->redirectToRoute('hats');
    }
}

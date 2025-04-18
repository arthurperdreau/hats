<?php

namespace App\Controller;

use App\Entity\Material;
use App\Form\MaterialType;
use App\Repository\MaterialRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MaterialController extends AbstractController
{
    #[Route('/materials', name: 'materials')]
    public function index(MaterialRepository $materialRepository): Response
    {
        return $this->render('material/index.html.twig', [
            'materials' => $materialRepository->findAll(),
        ]);
    }

    #[Route('/material/new', name: 'new_material')]
    public function create(Request $request, EntityManagerInterface $manager):Response
    {
        $material = new Material();
        $form = $this->createForm(MaterialType::class, $material);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($material);
            $manager->flush();
            return $this->redirectToRoute('materials');
        }
        return $this->render('material/create.html.twig', [
            'form' => $form->createView(),
        ]);

    }
}

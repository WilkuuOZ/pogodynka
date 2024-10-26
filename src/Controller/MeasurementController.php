<?php

// src/Controller/MeasurementController.php
namespace App\Controller;

use App\Entity\Measurement;
use App\Form\MeasurementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/measurement')]
class MeasurementController extends AbstractController
{
    #[Route('/', name: 'measurement_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $measurements = $entityManager->getRepository(Measurement::class)->findAll();

        return $this->render('measurement/index.html.twig', [
            'measurements' => $measurements,
        ]);
    }

    #[Route('/new', name: 'measurement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $measurement = new Measurement();
        $form = $this->createForm(MeasurementType::class, $measurement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($measurement);
            $entityManager->flush();

            return $this->redirectToRoute('measurement_index');
        }

        return $this->render('measurement/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'measurement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Measurement $measurement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MeasurementType::class, $measurement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('measurement_index');
        }

        return $this->render('measurement/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'measurement_delete', methods: ['POST'])]
    public function delete(Request $request, Measurement $measurement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $measurement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($measurement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('measurement_index');
    }
}


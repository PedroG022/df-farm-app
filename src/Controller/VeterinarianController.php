<?php

namespace App\Controller;

use App\Entity\Veterinarian;
use App\Form\VeterinarianType;
use App\Repository\VeterinarianRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

class VeterinarianController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private VeterinarianRepository $veterinarianRepository;

    public function __construct(EntityManagerInterface $entityManager, VeterinarianRepository $veterinarianRepository)
    {
        $this->entityManager = $entityManager;
        $this->veterinarianRepository = $veterinarianRepository;
    }

    #[Route('/veterinarians', name: 'index_veterinarian')]
    public function index(): Response
    {
        $veterinarians = $this->veterinarianRepository->findAll();

        return $this->render('veterinarian/index.html.twig', [
            'veterinarians' => $veterinarians,
        ]);
    }

    #[Route('/veterinarians/new', name: 'new_veterinarian')]
    public function new(Request $request): Response
    {
        $veterinarian = new Veterinarian();
        return $this->create_update_veterinarian('Adicionar veterinário', $request, $veterinarian);
    }

    #[Route('/veterinarians/edit/{id}', name: 'edit_veterinarian')]
    public function edit(Uuid $id, Request $request): Response
    {
        $veterinarian = $this->veterinarianRepository->findOneById($id);

        # Remove the veterinarian from the farms, as it will be updated
        # using the veterinarian farms field later. There is no problem
        # if the user cancels the process because it just updates if
        # the persist method is used.
        foreach ($veterinarian->getFarms() as $farm) {
            $farm->removeVeterinarian($veterinarian);
        }

        return $this->create_update_veterinarian('Editar veterinário', $request, $veterinarian);
    }

    #[Route('/veterinarians/delete/{id}', name: 'delete_veterinarian')]
    public function delete(Uuid $id, Request $request): Response
    {
        $database_veterinarian = $this->veterinarianRepository->findOneById($id);

        if ($database_veterinarian != null) {
            $this->entityManager->remove($database_veterinarian);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('index_veterinarian');
    }


    public function create_update_veterinarian(string $label, Request $request, Veterinarian $veterinarian): Response
    {
        $form = $this->createForm(VeterinarianType::class, $veterinarian, ['label' => $label]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $updated_veterinarian = $form->getData();

            if ($this->already_exists($updated_veterinarian)) {
                $this->addFlash('error', 'Já existe um veterinário com este CRM!');
            } else {
                foreach ($updated_veterinarian->getFarms() as $farm) {
                    $farm->addVeterinarian($updated_veterinarian);
                }

                return $this->persist_and_exit($updated_veterinarian);
            }
        }

        return $this->render('veterinarian/new.html.twig', [
            'form' => $form->createView(),
            'title' => $label,
        ]);
    }

    public function persist_and_exit(Veterinarian $veterinarian): Response
    {
        $this->entityManager->persist($veterinarian);
        $this->entityManager->flush();

        # Add flash message
        $this->addFlash(
            'success',
            'Operação concluída com sucesso!'
        );

        # Return to the home page
        return $this->redirectToRoute('index_veterinarian');
    }

    public function already_exists(Veterinarian $veterinarian): bool
    {
        $database_veterinarian = $this->veterinarianRepository->findOneByCrmv($veterinarian->getCrmv());

        if ($database_veterinarian != null && $veterinarian->getId() !== $database_veterinarian->getId()) {
            return true;
        }

        return false;
    }
}

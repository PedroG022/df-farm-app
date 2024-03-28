<?php

namespace App\Controller;

use App\Entity\Farm;
use App\Form\FarmType;
use App\Repository\FarmRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

class FarmController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private FarmRepository $farmRepository;

    public function __construct(EntityManagerInterface $entityManager, FarmRepository $farmRepository)
    {
        $this->entityManager = $entityManager;
        $this->farmRepository = $farmRepository;
    }

    # Route used to display the index page of
    # the farms.
    #[Route('/farms', name: 'index_farm')]
    public function index(): Response
    {
        $farms = $this->farmRepository->findAll();

        return $this->render('farm/index.html.twig', [
            'farms' => $farms,
        ]);
    }

    # Route used to display the page of
    # creation of a farm.
    #[Route('/farms/new', name: 'new_farm')]
    public function new(Request $request): Response
    {
        $target_farm = new Farm();
        return $this->create_update_farm('Adicionar fazenda', $request, $target_farm);
    }

    # Route used to display the edit page of
    # the farms.
    #[Route('/farms/edit/{id}', name: 'edit_farm')]
    public function edit(Uuid $id, Request $request): Response
    {
        $database_farm = $this->farmRepository->findOneById($id);
        return $this->create_update_farm('Atualizar fazenda', $request, $database_farm);
    }

    public function create_update_farm(string $label, Request $request, Farm $farm): Response
    {
        $form = $this->createForm(FarmType::class, $farm, ['label' => $label]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $updated_farm = $form->getData();

            if ($this->already_exists($updated_farm)) {
                return new Response('Já existe uma fazenda com este nome!', 400);
            }

            return $this->persist_and_exit($updated_farm);
        }

        return $this->render('farm/new.html.twig', [
            'form' => $form->createView(),
            'title' => $label
        ]);
    }

    # Method used to display the edit page of
    # the farms.
    #[Route('/farms/delete/{id}', name: 'delete_farm')]
    public function delete(Uuid $id): Response
    {
        $database_farm = $this->farmRepository->findOneById($id);

        if ($database_farm != null) {
            $this->entityManager->remove($database_farm);
            $this->entityManager->flush();

        }

        return $this->redirectToRoute('index_farm');
    }

    # Function used to persist the farm entity to the database,
    # notify the user of the changes and then redirect the user
    # to the farm index page.
    public function persist_and_exit(Farm $farm): Response
    {
        # Persist the entity to the database
        $this->entityManager->persist($farm);
        $this->entityManager->flush();

        # Add flash message
        $this->addFlash(
            'message',
            'Operação concluída com sucesso!'
        );

        # Return to the home page
        return $this->redirectToRoute('index_farm');
    }

    # Verifies if another farm with the corresponding params already exists
    # so that when updating a farm, the user can not set its name to something
    # already present in the database.
    public function already_exists(Farm $farm): bool
    {
        # Verification to see if there is already a farm with the same name
        $database_farm = $this->farmRepository->findOneByName($farm->getName());

        if ($database_farm != null && $database_farm->getId() !== $farm->getId()) {
            return true;
        }

        return false;
    }
}

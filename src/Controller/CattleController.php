<?php

namespace App\Controller;

use App\Entity\Cattle;
use App\Form\CattleType;
use App\Repository\CattleRepository;
use App\Service\GlobalVariables;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

class CattleController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private CattleRepository $cattleRepository;

    public function __construct(EntityManagerInterface $entityManager, CattleRepository $cattleRepository, PaginatorInterface $paginator)
    {
        $this->entityManager = $entityManager;
        $this->cattleRepository = $cattleRepository;
    }

    #[Route('/cattle', name: 'index_cattle')]
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->cattleRepository->findAllPaginated($page, GlobalVariables::PAGINATION_LIMIT);

        return $this->render('cattle/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    #[Route('/cattle/new', name: 'new_cattle')]
    public function new(Request $request): Response
    {
        $cattle = new Cattle();
        return $this->create_update_cattle('Adicionar gado', $request, $cattle);
    }

    #[Route('/cattle/edit/{id}', name: 'edit_cattle')]
    public function edit(Uuid $id, Request $request): Response
    {
        $cattle = $this->cattleRepository->findOneById($id);
        return $this->create_update_cattle('Atualizar gado', $request, $cattle);
    }

    #[Route('/cattle/delete/{id}', name: 'delete_cattle')]
    public function delete(Uuid $id): Response
    {
        $database_cattle = $this->cattleRepository->findOneById($id);

        if ($database_cattle != null) {
            $this->entityManager->remove($database_cattle);
            $this->entityManager->flush();

            $this->addFlash('success', 'Operação concluída com sucesso!');
        }

        return $this->redirectToRoute('index_cattle');
    }

    public function create_update_cattle(string $label, Request $request, Cattle $cattle): Response
    {
        $form = $this->createForm(CattleType::class, $cattle, ['label' => $label]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $form_cattle = $form->getData();

            if ($this->already_exists($form_cattle)) {
                $this->addFlash('error', 'Já existe um gado com este código!');
            } else if (!$this->can_add_to_farm($form_cattle)) {
                $this->addFlash('error', 'Esta fazenda já atingiu o número máximo de gado por hectáre');
            } else {
                $this->entityManager->persist($form_cattle);
                $this->entityManager->flush();

                $this->addFlash('success', 'Operação concluída com sucesso!');
                return $this->redirectToRoute('index_cattle');
            }
        }

        return $this->render('shared/new.html.twig', [
            'form' => $form->createView(),
            'title' => $label,
            'index_function' => 'index_cattle'
        ]);
    }

    public function can_add_to_farm(Cattle $cattle): bool
    {
        $farm = $cattle->getFarm();

        $farm_cattle_amount = $farm->getCattle()->count();
        $farm_hectare = $farm->getHectares();

        # The maximum amount of cattle per farm is decided by its amount
        # of hectares. A farm can have only 18 animals per hectare.
        $max_cattle_amount = $farm_hectare * GlobalVariables::MAX_CATTLE_PER_HECTARE;

        if ($farm_cattle_amount + 1 <= $max_cattle_amount) {
            return true;
        }

        # Verifies if the specified cattle is already in the farm
        # If it is, it means it is being updated, and so, we can
        # save it.
        if ($farm->getCattle()->contains($cattle)) {
            return true;
        }

        return false;
    }

    public function already_exists(Cattle $cattle): bool
    {
        $database_cattle = $this->cattleRepository->findOneByCode($cattle->getCode());

        if ($database_cattle != null && $database_cattle->getId() !== $cattle->getId()) {
            return true;
        }

        return false;
    }
}

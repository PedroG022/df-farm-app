<?php

namespace App\Controller;

use App\Repository\CattleRepository;
use App\Service\GlobalVariables;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

class SlaughterController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private CattleRepository $cattleRepository;

    public function __construct(EntityManagerInterface $entityManager, CattleRepository $cattleRepository)
    {
        $this->entityManager = $entityManager;
        $this->cattleRepository = $cattleRepository;
    }

    #[Route('/slaughter', name: 'index_slaughter')]
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->cattleRepository->findSlaughterReady($page, GlobalVariables::PAGINATION_LIMIT);

        return $this->render('slaughter/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/slaughter/{id}', name: 'slaughter_cattle')]
    public function slaughter_cattle(Uuid $id): Response
    {
        $cattle = $this->cattleRepository->findOneById($id);

        if ($cattle != null) {
            $cattle->setAlive(false);
            $this->entityManager->persist($cattle);
            $this->entityManager->flush();

            $this->addFlash('success', 'Operação concluída com sucesso!');
        } else {
            $this->addFlash('error', 'Houve um erro ao enviar o gado para o abate!');
        }


        return $this->redirectToRoute('index_slaughter');
    }

}

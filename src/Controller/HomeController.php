<?php

namespace App\Controller;

use App\Repository\CattleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    private CattleRepository $cattleRepository;

    public function __construct(CattleRepository $cattleRepository)
    {
        $this->cattleRepository = $cattleRepository;
    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $count = $this->cattleRepository->slaughteredAmount();
        $milk_amount = $this->cattleRepository->milkPerWeek();
        $feed_per_week = $this->cattleRepository->feedPerWeek();
        $feed_exceeds = $this->cattleRepository->feedPerWeekIsHigherThan500andOneYearOld();

        if ($count == null)
            $count = 0;

        if ($milk_amount == null)
            $milk_amount = 0 ;

        if ($feed_per_week == null)
            $feed_per_week = 0 ;

        if ($feed_exceeds == null)
            $feed_exceeds = 0 ;

        return $this->render('home/index.html.twig', [
            'slaughtered_count' => $count,
            'milk_amount' => $milk_amount,
            'feed_per_week' => $feed_per_week,
            'feed_exceeds' => $feed_exceeds
        ]);
    }
}

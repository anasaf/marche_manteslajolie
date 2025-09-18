<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlanController extends AbstractController
{
    #[Route('/plan', name: 'plan')]
    public function index(): Response
    {
        return $this->render('plan/index.html.twig');
    }

    #[Route('/detail/{id}', name: 'detail')]
    public function detail(int $id): Response
    {
        return $this->render('plan/detail.html.twig', [
            'id' => $id,
        ]);
    }


}

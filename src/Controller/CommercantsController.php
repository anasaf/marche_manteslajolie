<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class CommercantsController extends AbstractController
{
    #[Route('/commercants/{categorie?}', name: 'commercants')]
    public function index(string $categorie = null): Response
    {
        // Exemple statique (à remplacer plus tard par une base de données)
        $commercants = [
            'mode' => [
                ['nom' => 'Ali vetements femme', 'image' => 'https://picsum.photos/400/250?random=1'],
                ['nom' => 'Mohamed Ligne de maison', 'image' => 'https://picsum.photos/400/250?random=2'],
            ],
            'alimentaire' => [
                ['nom' => 'Berbere fruit legumes', 'image' => 'https://picsum.photos/400/250?random=3'],
                ['nom' => 'Nanas Coffee', 'image' => 'https://picsum.photos/400/250?random=4'],
            ],
            'services' => [
                ['nom' => 'Jibby et Nordine a votre ecoute', 'image' => 'https://picsum.photos/400/250?random=5'],
                ['nom' => 'Mediateur mantes', 'image' => 'https://picsum.photos/400/250?random=6'],
            ]
        ];

        $categorie = $categorie ?? 'mode'; // Par défaut "mode"
        $liste = $commercants[$categorie] ?? [];

        return $this->render('commercants/index.html.twig', [
            'categorie' => $categorie,
            'commercants' => $liste,
        ]);
    }


}

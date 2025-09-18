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
                ['nom' => 'Ali vetements femme', 'image' => 'robes_femmes.jpeg'],
                ['nom' => 'Mohamed Ligne de maison', 'image' => 'linge_maison.jpeg'],
            ],
            'alimentaire' => [
                ['nom' => 'Berbere fruit legumes', 'image' => 'fruit_legumes.jpeg'],
                ['nom' => 'Nanas Coffee', 'image' => 'coffee.jpg'],
            ],
            'services' => [
                ['nom' => 'Jibby et Nordine a votre ecoute', 'image' => 'service.png'],
                ['nom' => 'Mediateur mantes', 'image' => 'mediateur.jpg'],
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

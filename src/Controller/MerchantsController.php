<?php
declare(strict_types=1);
namespace App\Controller;

use App\Entity\Category;
use App\Entity\Merchant;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MerchantsController extends AbstractController
{
    #[Route('/merchants/{categorie?}', name: 'merchants')]
    public function index(string $categorie = null): Response
    {
        // Exemple statique (à remplacer plus tard par une base de données)
        $merchants = [
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
        $liste = $merchants[$categorie] ?? [];

        return $this->render('merchant/index.html.twig', [
            'categorie' => $categorie,
            'merchants' => $liste,
        ]);
    }

    #[Route('/merchants/detail/{id}', name: 'merchant_detail')]
    public function detail(string $id, EntityManagerInterface $em,  PaginatorInterface $paginaton): Response
    {
        $merchant = $em->getRepository(Merchant::class)->findOneBy(['id' => 1]);
        $categories = $em->getRepository(Category::class)->findAll();
        $paginaton->paginate($merchant->getProducts(), 1, 8);

        return $this->render('merchant/sephora.html.twig',
            [
                'merchant'=>$merchant,
                'products'=>$paginaton->paginate($merchant->getProducts(), 1, 8),
                'categories'=> $categories
            ]
        );
    }





}

<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Category;
use App\Entity\Merchant;
use App\Entity\Product;
use Doctrine\Common\Collections\ArrayCollection;
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
    public function detail(string $id, PaginatorInterface $paginaton): Response
    {
        $category =  (New Category())
            ->setName('parfum')
            ->setId(1);
        $merchant = (new Merchant())->setId($id)
            ->addProducts((new Product())
                ->setId(1)
                ->setName('casquette')
                ->setDescription('description')
                ->setImageName('toto')
                ->setPrice(300)
                ->setStock(30)
                ->setCategory($category)
            )->setAddress(
                (new Address())
                    ->setAddress(" rue du cedre bleu")
                    ->setCity("mantes")
                    ->setPostalCode(78200)
                    ->setIsActive(true)
            )
            ->setName('Maison du monde')
            ->setDescription('belle vaisselle')
        ;

        $merchant->addProducts((new Product())
            ->setId(2)
            ->setName('belle')
            ->setDescription('description')
            ->setImageName('toto')
            ->setPrice(200)
            ->setStock(30)
            ->setCategory($category));

        $merchant->addProducts((new Product())
            ->setId(3)
            ->setName('nuit')
            ->setDescription('description')
            ->setImageName('toto')
            ->setPrice(100)
            ->setStock(30)
            ->setCategory($category));

        $merchant->addProducts((new Product())
            ->setId(4)
            ->setName('toto')
            ->setDescription('description')
            ->setImageName('toto')
            ->setPrice(150)
            ->setStock(30)
            ->setCategory($category));

        $merchant->setDescription('description');
        $categories = (new ArrayCollection());
        $categories
            ->add(
                $category
            )
        ;

        $categories->add(
            (New Category())
                ->setName('gel douche')
                ->setId(2)
        );

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

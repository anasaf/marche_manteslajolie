<?php

namespace App\Controller\Admin;

use App\Entity\Commercant;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Order;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(CommercantCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Gestion du Marché');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Gestion des Commerçants & Produits');
        yield MenuItem::linkToCrud('Commerçants', 'fas fa-store', Commercant::class);
        yield MenuItem::linkToCrud('Produits', 'fas fa-box', Product::class);
        yield MenuItem::linkToCrud('Catégories', 'fas fa-tags', Category::class);

    //    yield MenuItem::section('Commandes & Utilisateurs');
      //  yield MenuItem::linkToCrud('Commandes', 'fas fa-shopping-cart', Order::class);
       // yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class);
    }
}

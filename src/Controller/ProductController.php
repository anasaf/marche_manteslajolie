<?php
declare(strict_types=1);

namespace App\Controller;
use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/products', name: 'product_list')]
    public function list(
        Request $request,
        PaginatorInterface $paginator,
        EntityManagerInterface $em
    ): Response {
        // Filtres
        $search = $request->query->get('search', '');
        $categoryId = 2; //$request->query->get('category');
        $sort = $request->query->get('sort', 'relevance');

        // Query builder
        $qb = $em->getRepository(Product::class)->createQueryBuilder('p')
            ->leftJoin('p.category', 'c')
            ->addSelect('c');

        if (!empty($search)) {
            $qb->andWhere('p.name LIKE :search OR p.description LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        if (!empty($categoryId)) {
            $qb->andWhere('c.id = :catId')
                ->setParameter('catId', $categoryId);
        }

        // Tri
        switch ($sort) {
            case 'price_asc':
                $qb->orderBy('p.price', 'ASC');
                break;
            case 'price_desc':
                $qb->orderBy('p.price', 'DESC');
                break;
            case 'newest':
                $qb->orderBy('p.createdAt', 'DESC');
                break;
            case 'best_sellers':
                $qb->orderBy('p.salesCount', 'DESC');
                break;
            default: // relevance ou autre
                $qb->orderBy('p.id', 'DESC'); // fallback
                break;
        }

        // Pagination avec KNP
        $pagination = $paginator->paginate(
            $qb->getQuery(),
            $request->query->getInt('page', 1),
            10 // produits par page
        );

        // Catégories pour le filtre
        $categories = $em->getRepository(Category::class)->findAll();

        return $this->render('product/list.html.twig', [
            'products' => $pagination,
            'categories' => $categories,
            'current_category' => $categoryId,
            'current_search' => $search,
            'current_sort' => $sort,
        ]);
    }
}

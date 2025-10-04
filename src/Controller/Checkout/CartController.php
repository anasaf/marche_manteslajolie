<?php
declare(strict_types=1);
namespace  App\Controller\Checkout;

use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cart')]
class CartController extends AbstractController
{
    private CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;

    }


    #[Route('/detail', name: 'cart_index')]
    public function index(): Response
    {
        return $this->render('cart/index.html.twig', [
            'items' => $this->cartService->getCartItems(),
            'total' => $this->cartService->getTotal(),
        ]);
    }

    #[Route('/add/{id}', name: 'cart_add')]
    public function add(int $id): Response
    {
        $this->cartService->add($id);
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/decrement/{id}', name: 'cart_decrement')]
    public function decrement( int $id): Response
    {
        $this->cartService->decrement($id);
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/remove/{id}', name: 'cart_remove')]
    public function remove(int $id): Response
    {
        $this->cartService->remove($id);
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/clear', name: 'cart_clear')]
    public function clear(): Response
    {
        $this->cartService->clear();
        return $this->redirectToRoute('cart_index');
    }

}

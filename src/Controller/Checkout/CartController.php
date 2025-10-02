<?php

namespace  App\Controller\Checkout;

use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Cart;
use Symfony\Component\Uid\Uuid;

#[Route('/cart')]
class CartController extends AbstractController
{
    #[Route('/{token?""}', name: 'cart_view', requirements: ['token' => '.+'],  methods: ['GET'])]
    public function view(string $token=null, EntityManagerInterface $em): Response
    {
        if( $token == null){
            $token = new Uuid();

        }
       // $cart = $em->getRepository(Cart::class)->findOneBy(['token' => $token]);
        $cart= new Cart($token->toString());
        if (!$cart) {
            throw $this->createNotFoundException('Panier introuvable');
        }

        return $this->render('cart/view.html.twig', [
            'cart' => $cart,
        ]);
    }


    #[Route('/detail', name: 'cart_index')]
    public function index(CartService $cartService): Response
    {
        return $this->render('cart/index.html.twig', [
            'items' => $cartService->getCartItems(),
            'total' => $cartService->getTotal(),
        ]);
    }

    #[Route('/add/{id}', name: 'cart_add')]
    public function add(CartService $cartService, int $id): Response
    {
        $cartService->add($id);
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/decrement/{id}', name: 'cart_decrement')]
    public function decrement(CartService $cartService, int $id): Response
    {
        $cartService->decrement($id);
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/remove/{id}', name: 'cart_remove')]
    public function remove(CartService $cartService, int $id): Response
    {
        $cartService->remove($id);
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/clear', name: 'cart_clear')]
    public function clear(CartService $cartService): Response
    {
        $cartService->clear();
        return $this->redirectToRoute('cart_index');
    }

}

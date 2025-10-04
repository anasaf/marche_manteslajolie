<?php
declare(strict_types=1);
namespace App\Service;


use App\Entity\Product;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\ProductRepository;

class CartService
{
    private SessionInterface $session;

    public function __construct(RequestStack $requestStack, private ProductRepository $productRepository)
    {
        $this->session = $requestStack->getSession();
    }

    public function add(int $id): void
    {
        $cart = $this->session->get('cart', []);

        if (!isset($cart[$id])) {
            $cart[$id] = 0;
        }
        $cart[$id]++;

        $this->session->set('cart', $cart);
    }

    public function decrement(int $id): void
    {
        $cart = $this->session->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]--;
            if ($cart[$id] <= 0) {
                unset($cart[$id]);
            }
        }
        $this->session->set('cart', $cart);
    }

    public function remove(int $id): void
    {
        $cart = $this->session->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
        }
        $this->session->set('cart', $cart);
    }

    public function clear(): void
    {
        $this->session->set('cart', []);
    }

    public function getCartItems(): array
    {
        $cart = $this->session->get('cart', []);
        $items = [];

        foreach ($cart as $id => $quantity) {
            $product = $this->productRepository->find($id);
            if ($product) {
                $items[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                ];
            }
        }

        return $items;
    }

    public function getTotal(): float
    {
        $total = 0;
        foreach ($this->getCartItems() as $item) {
            $total += $item['product']->getPrice() * $item['quantity'];
        }

        return $total;
    }

    public function getCount(): int
    {
        $cart = $this->session->get('cart', []);
        return array_sum($cart);
    }
}



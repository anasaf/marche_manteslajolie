<?php

namespace  App\Controller\Checkout;

use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Cart;
use App\Service\StripeGateway;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CheckoutController extends AbstractController
{
    #[Route('/t_{token}', name: 'acme_checkout', methods: ['GET'])]
    public function checkout(string $token, EntityManagerInterface $em, StripeGateway $stripe): Response
    {
        //$cart = $em->getRepository(Cart::class)->findOneBy(['token' => $token]);
        $cart = new Cart($token);

        if (!$cart) {
            throw $this->createNotFoundException('Panier introuvable');
        }

        $intent = $stripe->createPaymentIntent($cart->getTotal(), 'eur', ['cart_id' => $cart->getId()]);

        return $this->render('checkout/index.html.twig', [
            'cart' => $cart,
            'clientSecret' => $intent->client_secret,
            'publishableKey' => $stripe->getPublishableKey(),
        ]);
    }


    #[Route("/payment", name:"checkout")]
    public function createCheckout(): Response
    {
        try {

            // Charge la bibliothèque Stripe
           // require_once $this->getParameter('kernel.project_dir') . '/vendor/stripe/stripe-php/init.php';

            // Définit la clé API
            Stripe::setApiKey($_ENV['STRIPE_SECRET']);

            // Affiche la clé utilisée (temporaire, pour déboguer)
            $this->addFlash('notice', 'Using key: ' . substr($_ENV['STRIPE_SECRET'], 0, 8) . '...');
            // Crée la session de checkout
            $checkout_session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'unit_amount' => 2000,
                        'product_data' => [
                            'name' => 'Produit test',
                            'description' => 'Description du produit test',
                        ],
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => $this->generateUrl('payment_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
                'cancel_url' => $this->generateUrl('payment_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL),
            ]);

            // Redirige vers Stripe
            return $this->redirect($checkout_session->url, 303);

        } catch (\Exception $e) {
            // Affiche l'erreur en détail
            return new Response('Erreur: ' . $e->getMessage() . '<br>Trace: ' . $e->getTraceAsString());
        }
    }

    #[Route('/success', name: 'payment_success', methods: ['GET'])]
    public function success(): Response
    {
        return $this->render('checkout/success.html.twig');
    }

    #[Route('/cancel', name: 'payment_cancel', methods: ['GET'])]
    public function paymentCancel(): Response
    {
        return $this->render('checkout/cancel.html.twig');
    }
}

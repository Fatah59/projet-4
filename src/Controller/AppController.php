<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\BuyerOrderType;
use App\Form\ContactType;
use App\Entity\Order;
use App\Form\OrderType;
use App\Entity\Visitor;
use App\Form\VisitorsType;
use App\Entity\Buyer;
use App\Form\BuyerType;
use App\Service\CartService;
use App\Service\MailerService;
use App\Service\StripeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{

    /**
     * @var CartService
     */

    private $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * @Route ("/", name="home")
     * @return Response
     */

    public function indexAction(Request $request)
    {
        return $this->render('pages/home.html.twig');
    }


    /**
     * @Route("/order", name="order")
     */

    public function orderAction(Request $request)
    {
        $cart = $this->cartService->get() ?? new Order();

        $orderForm = $this->createForm(OrderType::class, $cart);
        $orderForm->handleRequest($request);

        if ($orderForm->isSubmitted() && $orderForm->isValid()) {
            $this->cartService->save($cart);

            return $this->redirectToRoute('visitors');
        }

        return $this->render('app/order.html.twig', [
            'orderForm' => $orderForm->createView()
        ]);
    }

    /**
     * @Route("/visitors", name="visitors")
     */
    public function visitorsAction(Request $request)
    {
        $cart = $this->cartService->get();

        if (!$cart) {
            return $this->redirectToRoute('/order');
        }

        $visitorsForm = $this->createForm(VisitorsType::class, $cart);
        $visitorsForm->handleRequest($request);
        if ($visitorsForm->isSubmitted() && $visitorsForm->isValid()) {
            dump($cart);
            $this->cartService->save($cart);

            return $this->redirectToRoute('buyer');
        }


        return $this->render('app/visitors.html.twig', [
            'visitorForm' => $visitorsForm->createView()
        ]);
    }

    /**
     * @Route ("/buyer", name="buyer")
     * @return Response
     */

    public function buyerAction(Request $request)
    {
        $cart = $this->cartService->get();

        if (!$cart) {
            return $this->redirectToRoute('/visitors');
        }

        $buyerForm = $this->createForm(BuyerOrderType::class, $cart);
        $buyerForm->handleRequest($request);
        if ($buyerForm->isSubmitted() && $buyerForm->isValid()) {
            $this->cartService->save($cart);

            return $this->redirectToRoute('pay');
        }

        return $this->render('app/buyer.html.twig', [
            'buyerForm' => $buyerForm->createView()
        ]);
    }

    /**
     * @Route ("/pay", name="pay")
     * @return Response
     */

    public function payAction(Request $request, StripeService $stripeService)
    {
        $cart = $this->cartService->get();

        if (!$cart) {

            return $this->redirectToRoute('/buyer');
        }
         $this->cartService->updatePrices($cart);

        $session = $stripeService->createSession($cart);

        return $this->render('app/pay.html.twig', [
            'session' => $session,
            'stripePublishableKey' => $stripeService->getStripePublishableKey()
        ]);
    }


    /**
     * @Route ("/contact", name="contact")
     * @return Response
     */

    public function contactAction(Request $request, MailerService $mailerService)
    {
        $contact = new Contact();
        $contactForm = $this->createForm(ContactType::class, $contact);
        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $mailerService->mailAction($contact);

            $this->addFlash('success', 'Votre message a bien été envoyé');

            return $this->redirectToRoute('contact');
        }

        return $this->render('pages/contact.html.twig', [
            'contactForm' => $contactForm->createView()
        ]);

    }

    /**
     * @Route ("/informations", name="informations")
     * @return Response
     */

    public function informationsAction(Request $request)
    {
        return $this->render('pages/informations.html.twig');
    }

    /**
     * @Route ("/pay/success", name="payment_success")
     * @return Response
     */

    public function paymentSuccess(Request $request)
    {
        return $this->json('success');
    }

    /**
     * @Route ("/pay/cancel", name="payment_cancel")
     * @return Response
     */

    public function paymentCancel(Request $request)
    {
        return $this->json('cancel');
    }

}
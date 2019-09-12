<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use App\Entity\Visitor;
use App\Form\VisitorType;
use App\Entity\Buyer;
use App\Form\BuyerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{

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
        $order = new Order();
        $orderForm = $this->createForm(OrderType::class, $order);
        $orderForm->handleRequest($request);

        if ($orderForm->isSubmitted() && $orderForm->isValid()) {
            $request->getSession()->set('order', $order);

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
        $order = $request->getSession()->get('order');

        if (!$order) {
            return $this->redirectToRoute('/order');
        }

        $visitor = new Visitor();
        $visitorForm = $this->createForm(VisitorType::class, $visitor);
        $visitorForm->handleRequest($request);

        if ($visitorForm->isSubmitted() && $visitorForm->isValid()) {
            $request->getSession()->set('visitor', $visitor);

            return $this->redirectToRoute('buyer');
        }


        return $this->render('app/visitors.html.twig', [
            'visitorForm' => $visitorForm->createView()
        ]);
    }

    /**
     * @Route ("/buyer", name="buyer")
     * @return Response
     */

    public function buyerAction(Request $request)
    {
        $visitor = $request->getSession()->get('visitor');

        if (!$visitor) {
            return $this->redirectToRoute('/visitors');
        }

        $buyer = new Buyer();
        $buyerForm = $this->createForm(BuyerType::class, $buyer);
        $buyerForm->handleRequest($request);

        if ($buyerForm->isSubmitted() && $buyerForm->isValid()) {
            $request->getSession()->set('visitor', $buyer);

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

    public function payAction(Request $request)
    {
        return $this->render('app/pay.html.twig');
    }

    /**
     * @Route ("/contact", name="contact")
     * @return Response
     */

    public function contactAction(Request $request)
    {
        return $this->render('pages/contact.html.twig');
    }

    /**
     * @Route ("/informations", name="informations")
     * @return Response
     */

    public function informationsAction(Request $request)
    {
        return $this->render('pages/informations.html.twig');
    }


}
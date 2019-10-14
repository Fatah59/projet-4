<?php

namespace App\Service;

use App\Entity\Order;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Component\Routing\RouterInterface;

class StripeService
{
    /**
     * @var string
     */
    private $stripeSecretKey;

    /**
     * @var string
     */
    private $stripePublishableKey;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(RouterInterface $router, string $stripeSecretKey, string $stripePublishableKey)
    {
        $this->router = $router;
        $this->stripeSecretKey = $stripeSecretKey;
        $this->stripePublishableKey =$stripePublishableKey;

        Stripe::setApiKey($this->stripeSecretKey);
    }

    public function createSession(Order $cart)
    {
        $lineItems = [];


        foreach ($cart->getVisitors() as $visitor){
            $lineItems[] = [
                'name' => $visitor->getName(),
                'amount' => $visitor->getPriceCents(),
                'quantity' => 1,
                'currency' => 'eur',
            ];
        }

        return Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'success_url' => $this->router->generate('payment_success', [], RouterInterface::ABSOLUTE_URL),
            'cancel_url' => $this->router->generate('payment_cancel', [], RouterInterface::ABSOLUTE_URL),
        ]);
    }

    /**
     * @return string
     */
    public function getStripePublishableKey(): string
    {
        return $this->stripePublishableKey;
    }

}
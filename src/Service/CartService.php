<?php


namespace App\Service;

use App\Entity\Order;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    private function getSession(): SessionInterface
    {
        return $this->requestStack->getCurrentRequest()->getSession();
    }

    public function save(Order $cart): void
    {
        $this->getSession()->set('cart', $cart);
    }

    public function get(): ?Order
    {
        return $this->getSession()->get('cart');
    }

}
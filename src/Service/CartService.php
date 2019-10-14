<?php


namespace App\Service;

use App\Entity\Order;
use DateTime;
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

    public function updatePrices(Order $cart): void
    {

        foreach ($cart->getVisitors() as $visitor) {

            $age = $visitor->getBirthDate()->diff(new DateTime())->y;
            if ($age< 4){
                $price = 0;
            }elseif ($age< 12) {
                $price = 800;
            }elseif ($age< 60){
                $price = 1600;
            }else {
                $price = 1200;
            }

            if (!$cart->getFullday()){
                $price/= 2;
            }

            $visitor->setPriceCents($price);
        }
    }

}
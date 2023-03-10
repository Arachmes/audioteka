<?php

namespace App\Controller\Cart;

use App\Entity\Cart;
use App\Entity\Product;
use App\Messenger\AddProductToCart;
use App\Messenger\MessageBusAwareInterface;
use App\Messenger\MessageBusTrait;
use App\ResponseBuilder\ErrorBuilder;
use App\Service\Cart\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cart/{cart}/{product}", methods={"PUT"}, name="cart-add-product")
 */
class AddProductController extends AbstractController implements MessageBusAwareInterface
{
    use MessageBusTrait;

    public function __construct(
        private ErrorBuilder $errorBuilder,
        private CartService $service,
        private LockFactory $lockFactory
    )
    {
    }

    public function __invoke(Cart $cart, Product $product): Response
    {

        $lock = $this->lockFactory->createLock(Cart::UPDATE_RESOURCE. $cart->getId());
        $lock->acquire(true);

        if ($cart->isFull()) {
            return new JsonResponse(
                $this->errorBuilder->__invoke('Cart is full.'),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $this->dispatch(new AddProductToCart($cart->getId(), $product->getId()));

        return new Response('', Response::HTTP_ACCEPTED);
    }
}

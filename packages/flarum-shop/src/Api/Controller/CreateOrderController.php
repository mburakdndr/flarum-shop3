<?php

namespace Flarum\Shop\Api\Controller;

use Flarum\Api\Controller\AbstractCreateController;
use Flarum\Shop\Model\Cart;
use Flarum\Shop\Model\Order;
use Flarum\Shop\Model\OrderItem;
use Flarum\Shop\Payment\PaymentManager;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class CreateOrderController extends AbstractCreateController
{
    protected $paymentManager;

    public $serializer = OrderSerializer::class;

    public function __construct(PaymentManager $paymentManager)
    {
        $this->paymentManager = $paymentManager;
    }

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $data = Arr::get($request->getParsedBody(), 'data', []);

        $cartItems = Cart::where('user_id', $actor->id)->with('product')->get();

        if ($cartItems->isEmpty()) {
            throw new ValidationException(['cart' => 'Cart is empty']);
        }

        $totalAmount = 0;
        foreach ($cartItems as $cartItem) {
            if ($cartItem->product->stock < $cartItem->quantity) {
                throw new ValidationException(['quantity' => 'Not enough stock for ' . $cartItem->product->name]);
            }
            $totalAmount += $cartItem->product->price * $cartItem->quantity;
        }

        $paymentMethod = Arr::get($data, 'attributes.payment_method');
        $paymentResult = $this->paymentManager->process($paymentMethod, $totalAmount, $data);

        if (!$paymentResult->isSuccessful()) {
            throw new ValidationException(['payment' => $paymentResult->getMessage()]);
        }

        $order = Order::create([
            'user_id' => $actor->id,
            'total_amount' => $totalAmount,
            'payment_method' => $paymentMethod,
            'payment_status' => $paymentResult->getStatus(),
            'status' => 'processing'
        ]);

        foreach ($cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->product->price
            ]);

            $cartItem->product->decrement('stock', $cartItem->quantity);
        }

        Cart::where('user_id', $actor->id)->delete();

        return $order;
    }
} 
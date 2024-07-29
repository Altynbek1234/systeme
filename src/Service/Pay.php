<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor;
use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor;

class Pay implements PaymentProcessorInterface
{
    public function pay (int $priceData, string $paymentProcessor): array
    {
        switch ($paymentProcessor) {
            case 'paypal':
                $payPal = new PaypalPaymentProcessor();
                $payPal->pay($priceData);
                break;
            case 'stripe':
                $stripe = new StripePaymentProcessor();
                $stripe->pay($priceData);
                break;
            default:
                throw new \InvalidArgumentException('Unsupported payment processor');
        }

        return [
            'message' => 'Purchase completed successfully',
            'price' => $priceData
        ];
    }

}

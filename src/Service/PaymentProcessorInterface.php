<?php
namespace App\Service;

interface PaymentProcessorInterface
{
    public function pay(int $priceData, string $paymentProcessor):array;
}
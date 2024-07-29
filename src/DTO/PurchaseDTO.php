<?php
namespace App\DTO;

use App\Validator\Constraints\ValidPaymentProcessor;
use Symfony\Component\Validator\Constraints as Assert;

class PurchaseDTO
{
    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    private mixed $product;

    #[Assert\NotNull]
    #[Assert\Type('string')]
    #[Assert\Regex(
        pattern: '/^DE\d{9}$|^IT\d{11}$|^GR\d{9}$|^FR[A-Z]{2}\d{9}$/',
        message: 'The tax number "{{ value }}" is not valid for the provided country.'
    )]
    public string $taxNumber;

    #[Assert\Type('string')]
    private ?string $couponCode;

    #[Assert\NotBlank]
    #[ValidPaymentProcessor]
    #[Assert\NotNull]
    private string $paymentProcessor;

    public function __construct(mixed $product, string $taxNumber, ?string $couponCode, string $paymentProcessor)
    {
        $this->product = $product;
        $this->taxNumber = $taxNumber;
        $this->couponCode = $couponCode;
        $this->paymentProcessor = $paymentProcessor;
    }

    public function getProduct(): int
    {
        return $this->product;
    }

    public function getTaxNumber(): string
    {
        return $this->taxNumber;
    }

    public function getCouponCode(): ?string
    {
        return $this->couponCode;
    }

    public function getPaymentProcessor(): string
    {
        return $this->paymentProcessor;
    }
}

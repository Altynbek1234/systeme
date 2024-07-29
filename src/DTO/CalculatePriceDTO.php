<?php
namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CalculatePriceDTO
{
    #[Assert\NotNull]
    #[Assert\Type('integer')]
    public mixed $product;

    #[Assert\NotNull]
    #[Assert\Type('string')]
    #[Assert\Regex(
        pattern: '/^DE\d{9}$|^IT\d{11}$|^GR\d{9}$|^FR[A-Z]{2}\d{9}$/',
        message: 'The tax number "{{ value }}" is not valid for the provided country.'
    )]    public string $taxNumber;

    #[Assert\Type('string')]
    public ?string $couponCode;

    public function __construct(mixed $product, string $taxNumber, ?string $couponCode
    ) {
        $this->product = $product;
        $this->taxNumber = $taxNumber;
        $this->couponCode = $couponCode;
    }

    public function getProduct(): mixed
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
}

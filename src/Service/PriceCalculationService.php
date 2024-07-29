<?php
namespace App\Service;

use App\DTO\CalculatePriceDTO;
use App\Repository\CouponRepository;
use App\Repository\ProductRepository;
use App\Repository\TaxRepository;

class PriceCalculationService implements PriceCalculationServiceInterface
{
    private ProductRepository $productRepository;
    private CouponRepository $couponRepository;
    private TaxRepository $taxRepository;

    public function __construct(
        ProductRepository $productRepository,
        CouponRepository $couponRepository,
        TaxRepository $taxRepository
    ) {
        $this->productRepository = $productRepository;
        $this->couponRepository = $couponRepository;
        $this->taxRepository = $taxRepository;
    }

    /**
     * @throws \Exception
     */
    public function calculatePrice(CalculatePriceDTO $dto): array
    {
        $productEntity = $this->productRepository->findProduct($dto->getProduct());
        if (!$productEntity) {
            throw new \Exception('Product not found');
        }

        $countryCode = substr($dto->getTaxNumber(), 0, 2);
        $countryEntity = $this->taxRepository->findTaxByCountry($countryCode);
        if (!$countryEntity) {
            throw new \Exception('Country not found');
        }

        $countryTax = $countryEntity->getRate();
        $price = $productEntity->getPrice();

        $couponCode = $dto->getCouponCode();
        if (!empty($couponCode)) {
            $couponEntity = $this->couponRepository->findCouponByCode($couponCode);
            if (!$couponEntity) {
                throw new \Exception('Coupon not found');
            }

            $discountType = $couponEntity->getDiscountType();
            $discountValue = $couponEntity->getDiscountValue();
            $price = $this->applyDiscount($price, $countryTax, $discountType, $discountValue);
        } else {
            $price = $this->applyTax($price, $countryTax);
        }

        $finalPrice = round($price, 2);
        $finalPrice = max($finalPrice, 0);
        return [
            'finalPrice' => $finalPrice
        ];
    }

    /**
     * @throws \Exception
     */
    private function applyDiscount(float $price, float $countryTax, string $discountType, float $discountValue): float
    {
        $price = $this->applyTax($price, $countryTax);
        return match ($discountType) {
            'fixedDiscountAmount' => $price - $discountValue,
            'purchaseAmountPercentage' => $price * (1 - $discountValue / 100),
            default => throw new \Exception('Invalid discount type'),
        };
    }

    private function applyTax(float $price, float $taxRate): float
    {
        return $price * (1 + $taxRate / 100);
    }
}

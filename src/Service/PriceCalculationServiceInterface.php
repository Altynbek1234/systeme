<?php
namespace App\Service;

use App\DTO\CalculatePriceDTO;
use Symfony\Component\HttpFoundation\Response;

interface PriceCalculationServiceInterface
{
    public function calculatePrice(CalculatePriceDTO $dto): array;
}

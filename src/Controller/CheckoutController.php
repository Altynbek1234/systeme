<?php
namespace App\Controller;

use App\DTO\CalculatePriceDTO;
use App\DTO\PurchaseDTO;
use App\Repository\OrderRepository;
use App\Service\PaymentProcessorInterface;
use App\Service\PriceCalculationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor;

class CheckoutController extends AbstractController
{
    private PriceCalculationService $priceCalculationService;
    private PaymentProcessorInterface $paymentProcessor;

    public function __construct(
        PriceCalculationService $priceCalculationService,
        PaymentProcessorInterface $paymentProcessor
    ) {
        $this->priceCalculationService = $priceCalculationService;
        $this->paymentProcessor = $paymentProcessor;
    }


//    #[Route('/calculate-price', name: 'calculate_price', methods: ['POST'])]
//
//    public function calculatePrice(
//        #[MapRequestPayload] CalculatePriceDTO $calculatePriceDTO,
//        ValidatorInterface $validator
//    ): JsonResponse
//    {
//        $violations = $validator->validate($calculatePriceDTO);
//
//        if (count($violations) > 0) {
//            $errors = [];
//            foreach ($violations as $violation) {
//                $errors[$violation->getPropertyPath()] = $violation->getMessage();
//            }
//
//            return new JsonResponse(['errors' => $errors], JsonResponse::HTTP_BAD_REQUEST);
//        }
//
//        return new JsonResponse(['message' => 'Price calculated successfully'], JsonResponse::HTTP_OK);
//    }

    #[Route('/calculate-price', name: 'calculate_price', methods: ['POST'])]
    public function calculatePrice(Request $request, ValidatorInterface $validator): Response
    {
        $data = json_decode($request->getContent(), true);

        $intValue = $data['product'];
        $product = is_numeric($intValue) && ctype_digit(strval($intValue)) ? intval($intValue) : $intValue;
        $taxNumber = $data['taxNumber'];
        $couponCode = $data['couponCode'] ?? null;

        $dto = new CalculatePriceDTO($product, $taxNumber, $couponCode);

        $violations = $validator->validate($dto);

        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }

            return new Response(
                json_encode(['errors' => $errors]),
                Response::HTTP_BAD_REQUEST,
                ['Content-Type' => 'application/json']
            );
        }

        try {
            $priceData = $this->priceCalculationService->calculatePrice($dto);
            return new Response(
                json_encode([
                    'message' => 'Price calculated successfully',
                    'price' => $priceData['finalPrice']
                ]),
                Response::HTTP_OK,
                ['Content-Type' => 'application/json']
            );
        } catch (\Exception $e) {
            return new Response(
                json_encode(['error' => $e->getMessage()]),
                Response::HTTP_BAD_REQUEST,
                ['Content-Type' => 'application/json']
            );
        }
    }

    /**
     * @throws \Exception
     */
    #[Route('/purchase', name: 'purchase', methods: ['POST'])]
    public function purchase(Request $request, ValidatorInterface $validator, OrderRepository $orderRepository)
    {
        $data = json_decode($request->getContent(), true);

        $intValue = $data['product'];
        $product = is_numeric($intValue) && ctype_digit(strval($intValue)) ? intval($intValue) : $intValue;
        $taxNumber = $data['taxNumber'];
        $couponCode = $data['couponCode'] ?? null;
        $paymentProcessor = $data['paymentProcessor'];

        $dto = new PurchaseDTO($product, $taxNumber, $couponCode, $paymentProcessor);

        $violations = $validator->validate($dto);

        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }

            return new Response(
                json_encode(['errors' => $errors]),
                Response::HTTP_BAD_REQUEST,
                ['Content-Type' => 'application/json']
            );
        }

        try {
            $calculatePriceDTO = new CalculatePriceDTO($product, $taxNumber, $couponCode);
            $priceData = $this->priceCalculationService->calculatePrice($calculatePriceDTO);
            $paymentResponse = $this->paymentProcessor->pay((int) $priceData['finalPrice'], $paymentProcessor);
            $orderRepository->saveOrder($product, $taxNumber, $couponCode, $paymentProcessor, $priceData['finalPrice']);

            return new Response(
                json_encode($paymentResponse),
                Response::HTTP_OK,
                ['Content-Type' => 'application/json']
            );
        } catch (\Exception $e) {
            return new Response(
                json_encode(['error' => $e->getMessage()]),
                Response::HTTP_BAD_REQUEST,
                ['Content-Type' => 'application/json']
            );
        }
    }
}

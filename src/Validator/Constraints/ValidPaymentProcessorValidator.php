<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Repository\PaymentProcessorRepository;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ValidPaymentProcessorValidator extends ConstraintValidator
{
    private PaymentProcessorRepository $paymentProcessorRepository;

    public function __construct(PaymentProcessorRepository $paymentProcessorRepository)
    {
        $this->paymentProcessorRepository = $paymentProcessorRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ValidPaymentProcessor) {
            throw new UnexpectedTypeException($constraint, ValidPaymentProcessor::class);
        }

        $availablePaymentProcessors = $this->paymentProcessorRepository->findAllProcessorNames();

        if (!in_array($value, $availablePaymentProcessors)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}

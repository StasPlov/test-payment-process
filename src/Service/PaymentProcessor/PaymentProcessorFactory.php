<?php

namespace App\Service\PaymentProcessor;

use App\Service\PaymentProcessor\Processor\PaypalPaymentProcessor;
use App\Service\PaymentProcessor\Processor\StripePaymentProcessor;

/**
 * @author Stas Plov <SaviouR.S@email.ru>
 */
class PaymentProcessorFactory {

    /**
     * Factory of Payment Processor
	 * 
     * @param string $paymentMethod
	 * 
     * @throws \Exception
     * @return PaymentProcessorInterface
     */
    public static function createPaymentProcessor(string $paymentMethod): PaymentProcessorInterface {
        switch ($paymentMethod) {
            case 'stripe':
                return new StripePaymentProcessor();
            case 'paypal':
                return new PaypalPaymentProcessor();
            default:
                throw new \Exception("Unknown payment method");
        }
    }
}
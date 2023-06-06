<?php

namespace App\Service\Processor\PaymentProcessor;

use App\Service\PaymentProcessor\PaymentProcessorInterface;

class PaypalPaymentProcessor implements PaymentProcessorInterface {

    public function pay(int $price): bool {
        if ($price > 100) {
            return false;
        }
		
		// some logic pay from paypal..

		return true;
    }
}
<?php

namespace App\Service\PaymentProcessor\Processor;

use App\Service\PaymentProcessor\PaymentProcessorInterface;

class StripePaymentProcessor implements PaymentProcessorInterface {

    public function pay(int $price): bool {
        if ($price <  10) {
            return false;
        }

		// some logic pay from other..

		return true;
    }
}
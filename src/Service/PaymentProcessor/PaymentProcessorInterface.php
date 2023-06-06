<?php

namespace App\Service\PaymentProcessor;

/**
 * @author Stas Plov <SaviouR.S@email.ru>
 */
interface PaymentProcessorInterface {

	/**
	 * Run pay process for some price
	 * 
	 * @param int $price
	 * @return bool true if payment was succeeded, false otherwise
	 */
	public function pay(int $price): bool;
}
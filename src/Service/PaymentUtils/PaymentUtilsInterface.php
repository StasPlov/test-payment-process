<?php

namespace App\Service\PaymentUtils;

use App\Entity\Coupon;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @author Stas Plov <SaviouR.S@email.ru>
 */
interface PaymentUtilsInterface {

	/**
	 * Сalculate total price
	 * 
	 * @param float $productPrice
	 * @param float $taxRate
	 * @param Coupon|null $coupon
	 * 
	 * @return float
	 */
	public function calculatePrice(float $productPrice, float $taxRate = 0, Coupon $coupon = null): float;

	/**
	 * Return Country object for tax number
	 * @param string $taxNumber
	 * 
	 * @throws BadRequestHttpException
	 * @return float
	 */
	public function getCountryTaxForTaxNumber(string $taxNumber): float;
	
	/**
	 * Check that the first two characters
	 * 
	 * @param string $string
	 * 
	 * @return bool
	 */
	public function checkTaxNumberFormat(string $taxNumber): bool;

	/**
	 * Return discount for coupon code
	 * 
	 * @param string $couponCode
	 * 
	 * @throws BadRequestHttpException
	 * @return Coupon
	 */
	public function getCoupon(string $couponCode): Coupon;
}
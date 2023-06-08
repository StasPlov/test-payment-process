<?php

namespace App\Service\PaymentUtils;

use App\Entity\CountryTax;
use App\Entity\Coupon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * More payment utils 
 */
class PaymentUtils implements PaymentUtilsInterface {

	/**
	 * Default doctrine ORM entityManager
	 * @var EntityManagerInterface
	 */
	public EntityManagerInterface $entityManager;

	public function __construct(
		EntityManagerInterface $entityManager
	) {
		$this->entityManager = $entityManager;
	}

	/**
	 * Сalculate total price
	 * 
	 * @param float $productPrice
	 * @param float $taxRate
	 * @param Coupon|null $coupon
	 * 
	 * @return float
	 */
	public function calculatePrice(float $productPrice, float $taxRate = 0, Coupon $coupon = null): float {
		$basePercent = ($productPrice / 100);

		$tax = $basePercent * $taxRate;
		$discount = 0;

		if(isset($coupon)) {
			$discount = $basePercent * $coupon->getDiscount();

			if($coupon->getType()->getId() === 1) { // fix discount
				$discount =  $coupon->getDiscount();
			}
		}

		$result = ($productPrice - $discount) + $tax;
		
		if($result <= 0) {
			$result = 0;
		}

		return round($result, 2);
	}

	/**
	 * Return Country object for tax number
	 * @param string $taxNumber
	 * 
	 * @throws BadRequestHttpException
	 * @return float
	 */
	public function getCountryTaxForTaxNumber(string $taxNumber): float {
		if(!$this->checkTaxNumberFormat($taxNumber)) {
			throw new BadRequestHttpException("Incorrect tax number");
		}
		
		$countryCode = preg_replace('/^([A-Z]{2}).*/ui', '$1', $taxNumber);

		/**
		 * @var CountryTax
		 */
		$countryTax = $this->entityManager->getRepository(CountryTax::class)->findOneBy(['country' => $countryCode]);
		
		if(empty($countryTax)) {
			throw new BadRequestHttpException("No matches were found for this tax number");
		}
		
		return $countryTax->getTax() ?? 0;
	}
	
	/**
	 * Check that the first two characters
	 * 
	 * @param string $string
	 * 
	 * @return bool
	 */
	public function checkTaxNumberFormat(string $taxNumber): bool {
		return preg_match('/^[a-zA-Z]{2}/ui', $taxNumber);
	}

	/**
	 * Return discount for coupon code
	 * 
	 * @param string $couponCode
	 * 
	 * @throws BadRequestHttpException
	 * @return Coupon
	 */
	public function getCoupon(string $couponCode): Coupon {
		$coupon = $this->entityManager->getRepository(Coupon::class)->findOneBy(['code' => $couponCode]);

		if(empty($coupon)) {
			throw new BadRequestHttpException("Incorrect coupon code");
		}

		return $coupon;
	}
}
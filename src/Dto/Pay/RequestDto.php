<?php

namespace App\Dto\Pay;

use App\Dto\DtoAbstract;
use App\Dto\DtoInterface;

/**
 * Base request from 
 * 
 * @author Stas Plov <SaviouR.S@email.ru>
 * 
 */
class RequestDto extends DtoAbstract implements DtoInterface {
    private ?int $product = null;
    private ?string $taxNumber = null;
    private ?string $couponCode = null;
	private ?string $paymentProcessor = null;

	/**
	 * @return int|null
	 */
	public function getProduct(): ?int {
		return $this->product;
	}
	
	/**
	 * @param int|null $product 
	 * @return self
	 */
	public function setProduct(?int $product): self {
		$this->product = $product;
		return $this;
	}
	
	/**
	 * @return string|null
	 */
	public function getTaxNumber(): ?string {
		return $this->taxNumber;
	}
	
	/**
	 * @param string|null $taxNumber 
	 * @return self
	 */
	public function setTaxNumber(?string $taxNumber): self {
		$this->taxNumber = $taxNumber;
		return $this;
	}
	
	/**
	 * @return string|null
	 */
	public function getCouponCode(): ?string {
		return $this->couponCode;
	}
	
	/**
	 * @param string|null $couponCode 
	 * @return self
	 */
	public function setCouponCode(?string $couponCode): self {
		$this->couponCode = $couponCode;
		return $this;
	}
	
	/**
	 * @return string|null
	 */
	public function getPaymentProcessor(): ?string {
		return $this->paymentProcessor;
	}
	
	/**
	 * @param string|null $paymentProcessor 
	 * @return self
	 */
	public function setPaymentProcessor(?string $paymentProcessor): self {
		$this->paymentProcessor = $paymentProcessor;
		return $this;
	}
}

<?php

namespace App\Controller;

use App\Dto\Pay\ProcessDto;
use App\Dto\Pay\RequestDto;
use App\Entity\Product;
use App\Service\PaymentProcessor\PaymentProcessorFactory;
use App\Service\PaymentUtils\PaymentUtilsInterface;
use App\Service\Validator\Annotation\ValidateDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use JsonException;

#[Route(path: '/api')]
class PaymentController extends AbstractController
{	
	private EntityManagerInterface $entityManager;
	private PaymentUtilsInterface $paymentUtils;

	public function __construct(
		EntityManagerInterface $entityManager,
		PaymentUtilsInterface $paymentUtils
	) {
		$this->entityManager = $entityManager;
		$this->paymentUtils = $paymentUtils;
	}

	#[ValidateDto(data: 'requestDto', class: RequestDto::class)]
	#[Route(path: '/pay', name: 'api-pay-get', methods: ['GET'], format: 'json')]
	public function getPay(
		RequestDto $requestDto
	): Response {
		try {
			$responce['data']['price'] = $this->calcPrice($requestDto);

			return new JsonResponse($responce, Response::HTTP_OK);
		} catch (\Throwable $th) {
			$errorCode = $th->getCode();

			if ($errorCode <= 0 || $errorCode > 526) {
				$errorCode = Response::HTTP_INTERNAL_SERVER_ERROR;
			}

			return new JsonResponse($th->getMessage(), $errorCode);
		}
	}

	#[ValidateDto(data: 'processDto', class: ProcessDto::class)]
	#[Route(path: '/pay', name: 'api-pay-process', methods: ['POST'], format: 'json')]
	public function processPay(
		ProcessDto $processDto
	): Response {
		try {
			$paymentProcessor = PaymentProcessorFactory::createPaymentProcessor($processDto->getPaymentProcessor());

			if(!$paymentProcessor->pay(
				$this->calcPrice($processDto)
			)) {
				throw new \Exception("Payment error", 400);
			}

			$responce = true;

			return new JsonResponse($responce, Response::HTTP_OK);
		} catch (\Throwable $th) {
			$errorCode = $th->getCode();

			if ($errorCode <= 0 || $errorCode > 526) {
				$errorCode = Response::HTTP_INTERNAL_SERVER_ERROR;
			}

			return new JsonResponse($th->getMessage(), $errorCode);
		}
	}

	private function calcPrice(object $dto): float {
		/**
		 * @var Product
		 */
		$product = $this->entityManager->getRepository(Product::class)->findOneBy(['id' => $dto->getProduct()]);

		if(empty($product)) {
			throw new JsonException("Products is empty", 400);
		}

		$coupon = null;
		if(!empty($dto->getCouponCode())) {
			$coupon = $this->paymentUtils->getCoupon($dto->getCouponCode());
		}

		return $this->paymentUtils->calculatePrice(
			$product->getPrice(),
			$this->paymentUtils->getCountryTaxForTaxNumber((string)$dto->getTaxNumber()),
			$coupon
		);
	}
}
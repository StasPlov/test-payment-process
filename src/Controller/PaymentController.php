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
	#[Route(path: '/pay', name: 'api-pay-get', methods: ['GET'])]
	public function getPay(
		RequestDto $requestDto
	): Response {
		try {
			/**
			 * @var Product
			 */
			$product = $this->entityManager->getRepository(Product::class)->findOneBy(['id' => $requestDto->getProduct()]);

			if(empty($product)) {
				throw new JsonException("Products is empty", 400);
			}

			$coupon = null;
			if(!empty($requestDto->getCouponCode())) {
				$coupon = $this->paymentUtils->getCoupon($requestDto->getCouponCode());
			}

			$responce['data']['price'] = $this->paymentUtils->calculatePrice(
				$product->getPrice(),
				$this->paymentUtils->getCountryTaxForTaxNumber((string)$requestDto->getTaxNumber()),
				$coupon
			);

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
	#[Route(path: '/pay', name: 'api-pay-process', methods: ['POST'])]
	public function processPay(
		ProcessDto $processDto
	): Response {
		try {

			$paymentProcessor = PaymentProcessorFactory::createPaymentProcessor($processDto->getPaymentProcessor());
			dd($processDto);
			/* $limiter = new Limiter($params->get_limit(), $params->get_offset());
			$order = new OrderBy($params->get_sortBy(), $params->get_orderBy());

			if ($params->get_nolimit()) {
				$limiter = null;
			}

			if (empty($params->get_sortBy()) && empty($params->get_orderBy())) {
				$order = null;
			}
			
			$responce['data'] = $entityManager->getRepository(Module::class)->findByEntityParam(
				id: $params->getId(),
				createdAt: $params->getCreatedAt(),
				updateAt: $params->getUpdateAt(),
				title: $params->getTitle(),
				description: $params->getDescription(),
				parentId: $params->getParentId(),
				breadcrumb: $params->getBreadcrumb(),
				isDelete: $params->getIsDelete(),
				isHide: $params->getIsHide(),
				canDelete: $params->getCanDelete(),
				isMain: $params->getIsMain(),
				orderBy: $order,
				limiter: $limiter
			);
			
			if ($params->get_count()) {
				$limiter = null;

				$responce['count'] = $entityManager->getRepository(Module::class)->getCountByEntityParam(
					id: $params->getId(),
					createdAt: $params->getCreatedAt(),
					updateAt: $params->getUpdateAt(),
					title: $params->getTitle(),
					description: $params->getDescription(),
					parentId: $params->getParentId(),
					breadcrumb: $params->getBreadcrumb(),
					isDelete: $params->getIsDelete(),
					isHide: $params->getIsHide(),
					canDelete: $params->getCanDelete(),
					isMain: $params->getIsMain(),
					orderBy: $order,
					limiter: $limiter
				);
			} */

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
}
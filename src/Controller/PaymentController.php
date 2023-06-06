<?php

namespace App\Controller;

use App\Dto\Pay\RequestDto;
use App\Repository\Sort\Limiter;
use App\Repository\Sort\OrderBy;
use App\Service\PaymentProcessor\PaymentProcessorFactory;
use App\Service\RequestDecoder\RequestDecoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route(path: '/api')]
class PaymentController extends AbstractController
{
	#[Route(path: '/pay', name: 'api-pay', methods: ['GET'])]
	public function getPay(
		Request $request,
		RequestDecoderInterface $requestDecoder,
		EntityManagerInterface $entityManager,
	): Response {
		try {
			/**
			 * @var RequestDto
			 */
			$params = $requestDecoder->decodeDto($request, RequestDto::class);
			dd($params);
		
			$limiter = new Limiter($params->get_limit(), $params->get_offset());
			$order = new OrderBy($params->get_sortBy(), $params->get_orderBy());

			if ($params->get_nolimit()) {
				$limiter = null;
			}

			if (empty($params->get_sortBy()) && empty($params->get_orderBy())) {
				$order = null;
			}
			
			$responce['data'] = $entityManager->getRepository(Product::class)->findByEntityParam(
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

	#[Route(path: '/pay', name: 'api-pay', methods: ['POST'])]
	public function processPay(
		Request $request,
		RequestDecoderInterface $requestDecoder,
		EntityManagerInterface $entityManager,
	): Response {
		try {
			/**
			 * @var RequestDto
			 */
			$params = $requestDecoder->decodeDto($request, RequestDto::class);
			dd($params);

			$paymentProcessor = PaymentProcessorFactory::createPaymentProcessor($params->getPaymentProcessor());

			/* 
				product:
				id  | name | description | price | isDelete | isHide 
				 1  |Iphone|     NULL    |  100  |   0      |  0
				 2  |Наушники|   NULL    |  20   |   0      |  0
				 3  | Чехол |    NULL    |  10   |   0      |  0


				country: (странны)
				code (unique) | name 
					DE	      | Германия
					IT	      | Италия
					GR	      | Греция

				country_tax: (налог для странны при покупки)
				country_id | tax
					DE     |  19
					IT     |  22
					GR     |  24

				coupons:
				code (unique) | type_id | discount
				    D15	      |   2     |    4
				    D215      |   1     |    8


				coupons_type:
				id | title
				 1 | фиксированная сумма скидки
				 2 | процент от суммы покупки



				(цена продукта 100 евро - 4% скидка c купона + налог 24%)

			*/
			
		
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
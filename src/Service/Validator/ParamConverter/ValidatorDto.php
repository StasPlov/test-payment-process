<?php

namespace App\Service\Validator\ParamConverter;

use App\Service\RequestDecoder\RequestDecoderInterface;
use App\Service\Validator\Annotations\ValidateDto;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @author Stas Plov <SaviouR.S@mail.ru>
 */
class ValidatorDto implements ParamConverterInterface {
    private $validator;
    private $requestStack;
	private $requestDecoder;

    public function __construct(
		ValidatorInterface $validator, 
		RequestStack $requestStack,
		RequestDecoderInterface $requestDecoder,
	) {
        $this->validator = $validator;
        $this->requestStack = $requestStack;
		$this->requestDecoder = $requestDecoder;
    }

    public function apply(Request $request, ParamConverter $configuration) {
        $data = $this->requestDecoder->decodeDto(
			$request, 
			$configuration->getClass()
		);
		dd($configuration);
        if (!$data) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        $errors = $this->validator->validate($data);
		
        if (count($errors) > 0) {
            $errorMessages = [];

            foreach ($errors as $error) {
                $errorMessages[] = [
                    'property' => $error->getPropertyPath(),
                    'message' => $error->getMessage()
                ];
            }

            throw new BadRequestHttpException(json_encode(['errors' => $errorMessages]));
        }

        $request->attributes->set($configuration->getClass(), $data);
		//dd($request->attributes);
        return true;
    }

    public function supports(ParamConverter $configuration) {
        return true;
    }
}
<?php

namespace App\Service\Validator\AnnotationHandler;

use App\Service\RequestDecoder\RequestDecoderInterface;
use App\Service\Validator\Annotation\ValidateDto;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use ReflectionAttribute;
use ReflectionMethod;
use ReflectionObject;

/**
 * This class validate end return DTO object in controller
 * 
 * @author Stas Plov <SaviouR.S@mail.ru>
 * 
 */
class ValidateDtoHandler {

	private RequestDecoderInterface $requestDecoder;
	private ValidatorInterface $validator;

    public function __construct(
		RequestDecoderInterface $requestDecoder,
		ValidatorInterface $validator,
	) {
		$this->requestDecoder = $requestDecoder;
        $this->validator = $validator;
    }

	public function onKernelController(ControllerEvent $event) {
		if (!$event->isMainRequest()) {
			return;
		}

		$controller = $event->getController();
		$request = $event->getRequest();
		

		if (is_array($controller)) {
			/**
			 * @var callable
			 */
			$controller = $controller[0];
		}
		
		$explodeController = explode('::', $request->get('_controller'));

		$requestMethodName = $explodeController[count($explodeController) - 1];

		if($requestMethodName == 'error_controller') { // exit for base exception
			return;
		}
	
		$reflectionObject = new ReflectionObject((object)$controller);
		$reflectionMethod = $reflectionObject->getMethod($requestMethodName);
		
		$attribute = $this->attributeHandler($reflectionMethod, ValidateDto::class);

		if(!isset($attribute)) { // if not validation attribute
			return;
		}

		$args = $attribute->getArguments();
		
		/**
		 * @var string
		 */
		$parameterName = $args['data'];

		/**
		 * @var object
		 */
		$data = $this->requestDecoder->decodeDto($request, $args['class']);

		$this->validate($data);

		$event->setController(
			static function() use ($reflectionMethod, $controller, $parameterName, $data) {
				return $reflectionMethod->invokeArgs($controller, [$parameterName => $data]);
			}
		);
	}

	private function validate(object $data): void {
		if(!$data) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        $errors = $this->validator->validate($data);

        if(count($errors) > 0) {
            $errorMessages = [];
			
            foreach ($errors as $error) {
                $errorMessages[] = [
                    'property' => $error->getPropertyPath(),
                    'message' => $error->getMessage()
                ];
            }

            throw new BadRequestHttpException(json_encode(['errors' => $errorMessages]));
        }
	}

	/**
	 * Get Method Parameter List
	 * 
	 * @param ReflectionMethod $method
	 * 
	 * @return array
	 */
	private function getMethodParameterList(ReflectionMethod $method): array {
		$result = [];
		foreach ($method->getParameters() as $parameter) {
			//dd($method);
			if (!array_key_exists($parameter->getType()->getName(), $result)) {
				//$result = [...$result, $parameter->getName()];
				$result = [
					...$result,
					$parameter->getName() => $parameter->getType()->getName()
				];
			}
		}
		return $result;
	}

	/**
	 * Summary of attributeHandler
	 * 
	 * @param ReflectionMethod $method
	 * @param string $annotationClass
	 * 
	 * @return ReflectionAttribute
	 */
	private function attributeHandler(ReflectionMethod $method, string $annotationClass): ?ReflectionAttribute  {
		/** 
		 * @var ArrayCollection<ReflectionAttribute>
		 */ 
		$result = (new ArrayCollection($method->getAttributes()))->filter(fn($i) => $i->getName() === $annotationClass);
		
		if($result->isEmpty()) {
			return null;
		}

		return $result[0];
	}
}
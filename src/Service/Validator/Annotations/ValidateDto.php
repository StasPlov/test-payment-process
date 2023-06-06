<?php

namespace App\Service\Validator\Annotations;

use Attribute;
use Symfony\Component\DependencyInjection\Attribute\Target;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
#[Attribute(Attribute::TARGET_METHOD)]
class ValidateDto {

    public $class;

	public function __construct(
		$class = ''
	) {
		$this->class = $class;
	}
}
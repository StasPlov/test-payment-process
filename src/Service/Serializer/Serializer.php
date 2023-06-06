<?php

namespace App\Service\Serializer;

use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Serializer as Serializer_;
use Symfony\Component\Serializer\SerializerInterface as SerializerInterface_;

/**
 * @author Stas Plov <SaviouR.S@email.ru>
 */
class Serializer implements SerializerInterface {

	private SerializerInterface_ $serializer;

    public function __construct(array $normalizers  = [], array $encoders = []) {
        $this->serializer = new Serializer_($normalizers, $encoders);
    }

    public function serialize(mixed $data, string $format, array $context = []): string {
		$context = [
			...$context,
			AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true,
			AbstractObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => fn() => null,
			AbstractObjectNormalizer::MAX_DEPTH_HANDLER => fn() => null,
			AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
		];
        // Serialize the data while checking for circular references
        return $this->serializer->serialize($data, $format, $context);
	}

    public function deserialize(mixed $data, string $type, string $format, array $context = []): mixed {
		return $this->serializer->deserialize($data, $type, $format, $context);
	}
}
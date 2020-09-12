<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Dto\CourierCreateInput;
use App\Entity\Courier;

final class CourierCreateInputDataTransformer implements DataTransformerInterface
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        $alreadyTransformed = $data instanceof CourierCreateInput;
        $outputMatches = Courier::class === $to;
        $inputMatches = CourierCreateInput::class === ($context['input']['class'] ?? null);

        return !$alreadyTransformed && $outputMatches && $inputMatches;
    }

    /**
     * {@inheritdoc}
     *
     * @param CourierCreateInput $data
     */
    public function transform($data, string $to, array $context = [])
    {
        $this->validator->validate($data);

        $entity = new Courier();
        $entity->setEmail($data->email);
        $entity->setFirstName($data->firstName);
        $entity->setLastName($data->lastName);

        return $entity;
    }
}

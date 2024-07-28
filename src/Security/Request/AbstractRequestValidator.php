<?php

namespace App\Security\Request;

use App\Exception\BusinessException;
use App\Serializer\Strategy\Json\JsonSerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Service\Attribute\Required;
use TypeError;

abstract class AbstractRequestValidator
{
    protected RequestStack $request;

    protected JsonSerializerInterface $serializer;

    protected ValidatorInterface $validator;


    #[Required]
    public function setRequest(RequestStack $request): void
    {
        $this->request = $request;
    }

    #[Required]
    public function setSerializer(JsonSerializerInterface $serializer): void
    {
        $this->serializer = $serializer;
    }

    #[Required]
    public function setValidator(ValidatorInterface $validator): void
    {
        $this->validator = $validator;
    }

    abstract public function getDto(): mixed;

    /**
     * @param Request $request
     * @param string $dtoClass
     * @return mixed
     */
    protected function deserializeRequest(Request $request, string $dtoClass): mixed
    {
        try {
            $test = $this->serializer->denormalize(
                [...$request->request->all(), ...$request->files->all()],
                $dtoClass,
                [
                    AbstractNormalizer::FILTER_BOOL => true,
                    AbstractNormalizer::REQUIRE_ALL_PROPERTIES => true,
                ]
            );
        }
        catch (ExceptionInterface $e) {
            if (method_exists($e, 'canUseMessageForUser') && $e->canUseMessageForUser()) {
                throw new BusinessException($e->getMessage());
            }
            throw new BusinessException("Неизвестная ошибка, проверьте входные параметры");
        }
        catch (TypeError) {
            throw new BusinessException("Неизвестная ошибка, проверьте входные параметры");
        }

        return $test;
    }

    protected function validate(mixed $data, ?array $constraints = null): void
    {
        $violations = $this->validator->validate($data, $constraints);

        if ($violations->count()) {

            $errors = [];

            foreach ($violations as $violation) {

                $property = strtolower(preg_replace('/[A-Z]/', '_\\0', lcfirst($violation->getPropertyPath())));

                if (empty($property)) {
                    $property = 'general';
                }

                $errors[$property][] = $violation->getMessage();
            }

            throw new BusinessException(
                null,
                200,
                $errors
            );
        }
    }
}
<?php
declare(strict_types=1);

namespace Lib\HttpFoundation;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class Fail implements Result
{
    /**
     * @var array Error[]
     */
    public array $errors;

    public function __construct(array $errors)
    {
        $this->errors = $errors;
    }

    public static function fromValidation(ConstraintViolationListInterface $errors): self
    {
        $result = [];
        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $result[] = new Error($error->getMessage(), $error->getMessageTemplate(), $error->getPropertyPath());
        }

        return new self($result);
    }
}

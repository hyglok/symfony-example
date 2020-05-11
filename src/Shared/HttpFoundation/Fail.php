<?php
declare(strict_types=1);

namespace Shared\HttpFoundation;

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
}

<?php
declare(strict_types=1);

namespace Lib\HttpFoundation;

final class Success implements Result
{
    public object $data;

    public function __construct(object $data)
    {
        $this->data = $data;
    }

    public static function ok(): Success
    {
        return new Success(
            new Ok()
        );
    }
}

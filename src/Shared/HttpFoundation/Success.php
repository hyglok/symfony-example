<?php

namespace Shared\HttpFoundation;

final class Success implements Result
{
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
}

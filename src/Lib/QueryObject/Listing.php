<?php
declare(strict_types=1);

namespace Lib\QueryObject;

class Listing
{
    public array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }
}
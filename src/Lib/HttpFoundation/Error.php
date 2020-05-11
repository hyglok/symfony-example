<?php
declare(strict_types=1);

namespace Lib\HttpFoundation;

final class Error
{
    public string $property;
    public string $message;
    public string $messageTemplate;

    public function __construct(string $message, string $messageTemplate = '', string $property = '')
    {
        $this->message = $message;
        $this->messageTemplate = $messageTemplate;
        $this->property = $property;
    }

}

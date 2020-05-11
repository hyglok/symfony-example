<?php
namespace Lib\Logger;

class RequestIdProcessor
{
    /**
     * @var RequestIdentifier
     */
    private $requestIdentifier;

    public function __construct(RequestIdentifier $requestIdentifier)
    {
        $this->requestIdentifier = $requestIdentifier;
    }

    public function processRecord(array $record)
    {
        $record['request_token'] = $this->requestIdentifier->getId();

        return $record;
    }
}
